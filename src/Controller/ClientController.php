<?php

namespace App\Controller;

use App\Entity\Client;
use DateTimeImmutable;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user/client')]
class ClientController extends AbstractController
{
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $this->clientRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = strtolower($sluggerInterface->slug($client->getName() . uniqid()));

            $client->setSlug($slug)
                ->setCreateAt(new DateTimeImmutable())
                ->setUpdateAt(new DateTimeImmutable())
                ->setUser($this->getUser())
                ->setUserId($this->getUser()->getId());

            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_client_show', methods: ['GET'])]
    public function show($slug): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $this->clientRepository->findOneBy(['slug' => $slug]),
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $slug, EntityManagerInterface $entityManager): Response
    {
        $client = $this->clientRepository->findOneBy(['slug' => $slug]);

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
