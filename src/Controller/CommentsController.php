<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/comments')]
final class CommentsController extends AbstractController
{
    #[Route(name: 'app_comments_index', methods: ['GET'])]
    public function index(CommentsRepository $commentsRepository): Response
    {
        $comments = $commentsRepository->findAll();

        return $this->render('comments/all_index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/new', name: 'app_comments_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comments();
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);
        /** @var \App\Entity\User $user */

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($user);
            $comment->setCreatedAt();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_comments_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comments/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/my_comments', name: 'app_comments_show', methods: ['GET'])]
    public function show(CommentsRepository $commentsRepository): Response
    {
        /** @var \App\Entity\User $user */

        $user = $this->getUser();

        if ($user) {
            // Récupérer toutes les réservations de l'utilisateur
            $comments = $commentsRepository->findByUser($user);

            return $this->render('comments/index.html.twig', [
                'comments' => $comments,
            ]);
        }

        // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
        return $this->redirectToRoute('app_login');
    }

    #[Route('/{id}/edit', name: 'app_comments_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comments $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comments/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comments_delete', methods: ['POST'])]
    public function delete(Request $request, Comments $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comments_index', [], Response::HTTP_SEE_OTHER);
    }
}
