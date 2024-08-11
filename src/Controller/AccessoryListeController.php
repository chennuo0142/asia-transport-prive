<?php

namespace App\Controller;

use App\Entity\AccessoryListe;
use App\Form\AccessoryListeType;
use App\Repository\AccessoryListeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/accessory/liste')]
class AccessoryListeController extends AbstractController
{
    #[Route('/', name: 'app_accessory_liste_index', methods: ['GET'])]
    public function index(AccessoryListeRepository $accessoryListeRepository): Response
    {
        return $this->render('accessory_liste/index.html.twig', [
            'accessory_listes' => $accessoryListeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_accessory_liste_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $accessoryListe = new AccessoryListe();
        $form = $this->createForm(AccessoryListeType::class, $accessoryListe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($accessoryListe);
            $entityManager->flush();

            return $this->redirectToRoute('app_accessory_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('accessory_liste/new.html.twig', [
            'accessory_liste' => $accessoryListe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_accessory_liste_show', methods: ['GET'])]
    public function show(AccessoryListe $accessoryListe): Response
    {
        return $this->render('accessory_liste/show.html.twig', [
            'accessory_liste' => $accessoryListe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_accessory_liste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AccessoryListe $accessoryListe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AccessoryListeType::class, $accessoryListe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_accessory_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('accessory_liste/edit.html.twig', [
            'accessory_liste' => $accessoryListe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_accessory_liste_delete', methods: ['POST'])]
    public function delete(Request $request, AccessoryListe $accessoryListe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $accessoryListe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($accessoryListe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_accessory_liste_index', [], Response::HTTP_SEE_OTHER);
    }
}
