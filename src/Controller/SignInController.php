<?php

namespace App\Controller;

use App\Form\DuckType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignInController extends AbstractController
{
    #[Route('/sign', name: 'app_sign_in')]
    public function index(): Response
    {
        $form = $this->createForm(DuckType::class);

        return $this->render('sign_in/index.html.twig', [
            'form' => $form,
        ]);
    }
}
