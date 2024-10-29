<?php

namespace App\Controller;

use Exception;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class BlogController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_blog', ['tri' => "derniers-articles"]);
    }

    #[Route('/admin/articles', name: 'app_admin_articles')]
    public function articles(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();
        return $this->render('admin/articles.html.twig', [
            "articles" => $articles
        ]);
    }
    
    #[Route('/blog/{tri}', name: 'app_blog')]
    public function articles_ordered(ArticleRepository $articleRepository, string $tri = "latest-articles"): Response
    {
        $user = $this->getUser();

        switch ($tri) {
            case "top-articles":
                $articles = $articleRepository->findByMostLiked();
                break;
            case "my-liked-articles":
                if ($user) {
                    $articles = $articleRepository->findByUserLikes($user);
                } else {
                    $articles = [];
                }
                break;
            case "latest-articles":
            default:
                $articles = $articleRepository->findByMostRecent();
        }
        
        return $this->render('blog/blog.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/articles/{id}', name: 'app_article')]
    public function article(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $article_repository = $entityManager->getRepository(Article::class);
        $article = $article_repository->find($id);

        $other_articles = [];
        $article_tags = $article->getTags();
        foreach($article_tags as $tag) {
            if (count($other_articles) >= 3) {
                break;
            }
            $tag_articles = $tag->getArticle();
            foreach ($tag_articles as $tag_article) {
                if (count($other_articles) < 3 && $tag_article->getId() != $article->getId() && !in_array($tag_article, $other_articles)) {
                    $other_articles[] = $tag_article;
                }
            }
        }
        
        $comment = new Comment();
        $comment_form = $this->createForm(CommentType::class, $comment);

        $comment_form->handleRequest($request);
        if ($comment_form->isSubmitted() && $comment_form->isValid()) {
            $comment->setArticle($article);
            $comment->setUser($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('app_article', ['id' => $id]);
        }

        return $this->render('blog/article.html.twig', [
            'article' => $article,
            'other_articles' => $other_articles,
            'comment_form' => $comment_form->createView()
        ]);
    }

    #[Route('/admin/creer-article', name: 'app_new_article')]
    public function new_article(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $article_form = $this->createForm(ArticleType::class, $article);
        $article_form->handleRequest($request);

        if ($article_form->isSubmitted() && $article_form->isValid()) {
            $img_article = $article_form->get('image')->getData();
            $article->setAuthor($this->getUser());

            if ($img_article) {
                $originalFilename = pathinfo($img_article->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$img_article->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $img_article->move(
                        $this->getParameter('article_img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    return new JsonResponse(["error" => "Something went wrong during the image upload."], Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $article->setImage($newFilename);
            }
            
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('app_article', ['id' => $article->getId()]);
        }

        return $this->render('admin/new-article.html.twig', [
            'article_form' => $article_form->createView()
        ]);
    }

    #[Route('/admin/articles/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function article_edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/edit_article.html.twig', [
            'article' => $article,
            'article_form' => $form,
        ]);
    }

    #[Route('/admin/article/delete/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function deleteArticle(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete-item'.$article->getId(), $request->request->get('_token'))) {
            try {
                $comments = $article->getComments();
                foreach ($comments as $comment) {
                    $entityManager->remove($comment);
                }
                $entityManager->remove($article);
                $entityManager->flush();
                return new JsonResponse(['message' => "Article was deleted successfully."], Response::HTTP_OK);
            } catch (Exception $e) {
                return new JsonResponse(['message' => "Something went wrong."], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse(['message' => "Invalid token."], Response::HTTP_BAD_REQUEST);
        }

        return $this->redirectToRoute('app_admin_articles', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/like/{id}', name: 'like_article')]
    public function likeArticle(Article $article, UserInterface $user, EntityManagerInterface $em): JsonResponse
    {
        if ($article->getLikes()->contains($user)) {
            $article->removeLike($user);
            $liked = false;
        } else {
            $article->addLike($user);
            $liked = true;
        }

        $em->persist($article);
        $em->flush();

        return new JsonResponse([
            'liked' => $liked,
            'totalLikes' => $article->getLikes()->count(),
        ]);
    }
}
