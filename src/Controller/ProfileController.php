<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Form\DuckType;
use App\Repository\CommentRepository;
use App\Repository\DuckRepository;
use App\Repository\QuackRepository;
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
        $this->denyAccessUnlessGranted('ROLE_USER', $duck);

        return $this->render('profile/profile.html.twig', [
            'duck' => $duck,
        ]);
    }

    #[Route('/{duck_id}', name: 'app_profile_show', methods: ['GET'])]
    public function show(Request $request, DuckRepository $duckRepository, QuackRepository $quackRepository, CommentRepository $commentRepository): Response
    {
        $duck = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER', $duck);

        $duckProfile = $duckRepository->find($request->get('duck_id'));
        $quacks = $quackRepository->findBy(array('duck' => $duckProfile->getId()));
        $quacks = count($quacks);

        $comments = $commentRepository->findBy(array('duck' => $duckProfile->getId()));
        $comments = count($comments);

        dump($quacks);
        return $this->render('profile/show.html.twig', [
            'duckProfile' => $duckProfile,
            'quacks' => $quacks,
            'comments' => $comments
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Duck $duck, DuckRepository $duckRepository, UserPasswordHasherInterface $passwordHasher): Response
    {

        $duck = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER', $duck);

        $this->denyAccessUnlessGranted('edit', $duckRepository->find($request->get('id')));

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

        $duck = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER', $duck);

        $this->denyAccessUnlessGranted('delete', $duckRepository->find($request->get('id')));

        if ($this->isCsrfTokenValid('delete' . $duck->getId(), $request->request->get('_token'))) {
            $duckRepository->remove($duck, true);
        }

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }


}