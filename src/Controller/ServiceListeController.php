<?php

namespace App\Controller;

use App\Entity\ServiceListe;
use App\Form\ServiceListeType;
use App\Repository\ServiceListeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/service/liste')]
class ServiceListeController extends AbstractController
{
    #[Route('/', name: 'app_service_liste_index', methods: ['GET'])]
    public function index(ServiceListeRepository $serviceListeRepository): Response
    {
        return $this->render('service_liste/index.html.twig', [
            'service_listes' => $serviceListeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_liste_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serviceListe = new ServiceListe();
        $form = $this->createForm(ServiceListeType::class, $serviceListe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serviceListe);
            $entityManager->flush();

            return $this->redirectToRoute('app_service_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_liste/new.html.twig', [
            'service_liste' => $serviceListe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_liste_show', methods: ['GET'])]
    public function show(ServiceListe $serviceListe): Response
    {
        return $this->render('service_liste/show.html.twig', [
            'service_liste' => $serviceListe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_service_liste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ServiceListe $serviceListe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceListeType::class, $serviceListe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_service_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_liste/edit.html.twig', [
            'service_liste' => $serviceListe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_liste_delete', methods: ['POST'])]
    public function delete(Request $request, ServiceListe $serviceListe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $serviceListe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($serviceListe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_service_liste_index', [], Response::HTTP_SEE_OTHER);
    }
}
