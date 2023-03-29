<?php

namespace App\Controller;

use App\Entity\Duck;
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

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, DuckRepository $duckRepository): Response
    {
        $quacks = [];
        $search = $request->get('search');

        if ($search != null) {
            $searchResult = $duckRepository->findByDuckName($search);
            if (count($searchResult) != 0 || $searchResult != null) {
                foreach ($searchResult as $author) {
                    foreach ($author->getQuacks()->getValues() as $value)
                    {
                        $quacks[] = $value;
                    }
                }
            }
        } else {
            $quacks = $entityManager->getRepository(Quack::class)->findAll();
        }

        dump($quacks);
        return $this->render('quack/quacks.html.twig', [
            'quacks_show' => $quacks,
            'search' => $search
        ]);
    }

    #[Route('/new', name: 'quack_create', methods: ['GET', 'POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request, QuackRepository $quackRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER', $this->getUser());

        $quack = new Quack();
        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quack->setDuck($this->getUser());
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

        $this->denyAccessUnlessGranted('ROLE_USER', $this->getUser());
        $this->denyAccessUnlessGranted('delete', $quack->getDuck());

        $quackRepository->remove($quack, true);

        return $this->redirectToRoute('index');
    }

    #[Route('/{quack}/edit', name: 'quack_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quack $quack, QuackRepository $quackRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER', $this->getUser());
        $this->denyAccessUnlessGranted('edit', $quack->getDuck());


        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quackRepository->save($quack, true);

            return $this->redirectToRoute('index');
        }

        return $this->render('quack/edit.html.twig', [
            'quack' => $quack,
            'form' => $form
        ]);
    }

    #[Route('/{quack}', name: 'quack_show', methods: ['GET'])]
    public function show(Quack $quack): Response
    {
        return $this->render('quack/show.html.twig', [
            'quack' => $quack
        ]);
    }

}
