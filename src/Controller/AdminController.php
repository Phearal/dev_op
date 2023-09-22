<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/admin.html.twig', [
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
                    return new JsonResponse(['error' => 'Erreur lors de l\'upload de l\'image.'], Response::HTTP_INTERNAL_SERVER_ERROR);
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

    #[Route('/admin/articles', name: 'app_admin_articles')]
    public function articles(): Response
    {
        return $this->render('admin/articles.html.twig', [
        ]);
    }

    #[Route('/admin/utilisateurs', name: 'app_admin_users')]
    public function users(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }
}
