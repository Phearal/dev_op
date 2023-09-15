<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog')]
    public function articles(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();
        
        return $this->render('blog/blog.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/articles/{id}', name: 'app_article')]
    public function article(EntityManagerInterface $entityManager, int $id): Response
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

        return $this->render('blog/article.html.twig', [
            'article' => $article,
            'other_articles' => $other_articles
        ]);
    }
}
