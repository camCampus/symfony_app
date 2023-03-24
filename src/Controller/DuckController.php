<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Form\DuckType;
use App\Repository\DuckRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/duck')]
class DuckController extends AbstractController
{
    #[Route('', name: 'app_duck_index', methods: ['GET'])]
    public function index(DuckRepository $duckRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',  $this->getUser());

        return $this->render('duck/index.html.twig', [
            'ducks' => $duckRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_duck_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DuckRepository $duckRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $duck = new Duck();
        $form = $this->createForm(DuckType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Hash Password
            $data = $form->getData();
            $txtPassword = $data->getPassword();
            $data->setPassword($passwordHasher->hashPassword($duck, $txtPassword));
            $duckRepository->save($duck, true);

            return $this->redirectToRoute('app_duck_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('duck/new.html.twig', [
            'duck' => $duck,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_duck_show', methods: ['GET'])]
    public function show(Duck $duck): Response
    {
        return $this->render('duck/show.html.twig', [
            'duck' => $duck,
        ]);
    }

    #[Route('/{id}', name: 'app_duck_delete', methods: ['POST'])]
    public function delete(Request $request, Duck $duck, DuckRepository $duckRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$duck->getId(), $request->request->get('_token'))) {
            $duckRepository->remove($duck, true);
        }

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_duck_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Duck $duck, DuckRepository $duckRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(DuckType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Hash Password
            $data = $form->getData();
            $txtPassword = $data->getPassword();
            $data->setPassword($passwordHasher->hashPassword($duck, $txtPassword));

            $duckRepository->save($duck, true);

            return $this->redirectToRoute('app_duck_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('duck/edit.html.twig', [
            'duck' => $duck,
            'form' => $form,
        ]);
    }
}
