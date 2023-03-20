<?php

namespace App\Controller;

use App\Entity\Quack;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\QuackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quacks')]
class QuackController extends AbstractController
{

    #[Route('', name: 'app_quack', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $quacks = $entityManager->getRepository(Quack::class)->findAll();
        dump($quacks);
        return $this->render('quack/quacks.html.twig', [
            'quacks_show' => $quacks
        ]);
    }

    #[Route('', name: 'app_createquack', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $quack = new Quack();
        $quack->setContend("new quack");

        $entityManager->persist($quack);
        $entityManager->flush();

        return $this->redirectToRoute('app_quack');
    }

    #[Route('/{quack}/delete', name: 'quack_delete')]
    public function delete(Quack $quack, QuackRepository $quackRepository)
    {
        $quackRepository->remove($quack, true);
        return $this->redirectToRoute('app_quack');
    }
}
