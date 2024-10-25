<?php

namespace App\Controller;

use Exception;
use App\Entity\Comment;
use App\Form\Comment1Type;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/', name: 'app_comment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($comment);
                $entityManager->flush();
                return new JsonResponse(['message' => "Comment was deleted successfully."], Response::HTTP_OK);
            } catch (Exception $e) {
                return new JsonResponse(['message' => "Something went wrong."], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse(['message' => "Invalid token."], Response::HTTP_BAD_REQUEST);
        }

        return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/validate/{id}', name: 'app_comment_validate', methods: ['POST'])]
    public function validate(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        try {
            $comment->setConfirmed(true);
            $entityManager->flush();
            return new JsonResponse(['message' => "Comment was validated successfully."], Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse(['message' => "Something went wrong."], Response::HTTP_BAD_REQUEST);
        }
    }
}
