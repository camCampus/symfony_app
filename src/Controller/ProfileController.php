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
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/profile')]
class ProfileController extends AbstractController
{

        private HttpClientInterface $client;
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/board', name: 'app_profile_profileboard', methods: ['GET'])]
    public function profileBoard(): Response
    {
        $duck = $this->getUser();
        return $this->render('profile/profile.html.twig', [
            'duck' => $duck,
            'img' => $this->fetchDuckImg(),
        ]);
    }


    #[Route('/{id}/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_profile_delete', methods: ['POST'])]
    public function delete(Request $request, Duck $duck, DuckRepository $duckRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$duck->getId(), $request->request->get('_token'))) {
            $duckRepository->remove($duck, true);
        }

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }
    private function fetchDuckImg(): array
    {
        $response = $this->client->request(
            'GET',
            'https://random-d.uk/api/v2/random'
        );
        return $response->toArray();
    }
}