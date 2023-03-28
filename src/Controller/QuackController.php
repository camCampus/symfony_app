<?php

namespace App\Controller;

use App\Entity\Quack;
use App\Form\QuackType;
use App\Repository\DuckRepository;
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
        dump($quacks);
        return $this->render('quack/quacks.html.twig', [
            'quacks_show' => $quacks,
        ]);
    }

    #[Route('/new', name: 'quack_create', methods: ['GET','POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request, QuackRepository $quackRepository): Response
    {

        $duck = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER', $duck);

        $quack = new Quack();
        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $quack->setDuck($duck);
            $quackRepository->save($quack, true);

            return $this->redirectToRoute('index');
        }

        return $this->render('quack/new.html.twig', [
            'quack' => $quack,
            'form' => $form,
        ]);

    }

    #[Route('/{quack}/delete', name: 'quack_delete', methods: ['POST'])]
    public function delete(Request $request, Quack $quack, QuackRepository $quackRepository): Response
    {
        $duck = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER', $duck);

        $id = $request->get('quack');
        $duckAuthor = $quackRepository->find($id)->getDuck();
        $this->denyAccessUnlessGranted('delete', $duckAuthor);

        $quackRepository->remove($quack, true);

        return $this->redirectToRoute('index');
    }

    #[Route('/{quack}/edit', name: 'quack_edit',  methods: ['GET', 'POST'])]
    public function edit(Request $request, Quack $quack, QuackRepository $quackRepository): Response
    {
        $duck = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER', $duck);

        $id = $request->get('quack');
        $duckAuthor = $quackRepository->find($id)->getDuck();
        $this->denyAccessUnlessGranted('edit', $duckAuthor);


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
