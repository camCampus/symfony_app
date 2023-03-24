<?php

namespace App\Controller;

use App\Entity\Quack;
use App\Form\QuackType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\QuackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quacks')]
class QuackController extends AbstractController
{

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $quacks = $entityManager->getRepository(Quack::class)->findAll();

        return $this->render('quack/quacks.html.twig', [
            'quacks_show' => $quacks
        ]);
    }

    #[Route('/new', name: 'quack_create', methods: ['GET','POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request, QuackRepository $quackRepository): Response
    {
        $quack = new Quack();
        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $quackRepository->save($quack, true);

            return $this->redirectToRoute('index');
        }

        return $this->render('quack/new.html.twig', [
            'quack' => $quack,
            'form' => $form,
        ]);

    }

    #[Route('/{quack}/delete', name: 'quack_delete')]
    public function delete(Quack $quack, QuackRepository $quackRepository): Response
    {
        $quackRepository->remove($quack, true);
        return $this->redirectToRoute('index');
    }

    #[Route('/{quack}/edit', name: 'quack_edit',  methods: ['GET', 'POST'])]
    public function edit(Request $request, Quack $quack, QuackRepository $quackRepository): Response
    {
        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $quackRepository->save($quack, true);

            return $this->redirectToRoute('index');
        }

        return $this->render('quack/edit.html.twig', [
            'quack' => $quack,
            'form' => $form
        ]);
    }

    #[Route('/{quack}', name:'quack_show', methods: ['GET'])]
    public function show(Quack $quack): Response
    {
        return $this->render('quack/show.html.twig', [
            'quack' => $quack
        ]);
    }
}
