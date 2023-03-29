<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Duck;
use App\Entity\Quack;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\DuckRepository;
use App\Repository\QuackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{


    #[Route('/{quack_id}/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommentRepository $commentRepository, QuackRepository $quackRepository): Response
    {

        $duck = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER', $duck);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setQuackId($quackRepository->find($request->get('quack_id')));

            $comment->setAuthor($duck->getDuckName());
            $comment->setDuck($duck);
            $commentRepository->save($comment, true);

            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,

        ]);
    }


    #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Duck $duck, Comment $comment, CommentRepository $commentRepository, DuckRepository $duckRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', $duck);

        $this->denyAccessUnlessGranted('edit', $commentRepository->find($request->get('id'))->getDuck());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->save($comment, true);

            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository, DuckRepository $duckRepository): Response
    {
        $duck = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER', $duck);

        $this->denyAccessUnlessGranted('is_author',  $commentRepository->find($comment));

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
        }

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }
}
