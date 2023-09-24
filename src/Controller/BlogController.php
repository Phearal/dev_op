<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_blog', ['tri' => "derniers-articles"]);
    }
    
    #[Route('/blog/{tri}', name: 'app_blog')]
    public function articles(EntityManagerInterface $entityManager, string $tri = "derniers-articles"): Response
    {
        switch ($tri) {
            case "top-articles":
                $articles = $entityManager->getRepository(Article::class)->findBy([], ['createdAt' => 'ASC']);
                break;
            case "derniers-articles":
                $articles = $entityManager->getRepository(Article::class)->findBy([], ['createdAt' => 'DESC']);
                break;
            default:
                $articles = $entityManager->getRepository(Article::class)->findBy([], ['createdAt' => 'DESC']);
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

    #[Route('/like/{id}', name: 'like_article')]
    public function likeArticle(Request $request, Article $article): Response
    {
        // ...
    }
}
