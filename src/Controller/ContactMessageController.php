<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactMessage1Type;
use App\Repository\ContactMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/message/contact')]
class ContactMessageController extends AbstractController
{
    private $contactMessageRepository;

    public function __construct(ContactMessageRepository $contactMessageRepository)
    {
        $this->contactMessageRepository = $contactMessageRepository;
    }

    #[Route('/', name: 'app_contact_message_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('contact_message/index.html.twig', [
            'contact_messages' => $this->contactMessageRepository->findAll(),
        ]);
    }

    #[Route('/{slug}', name: 'app_contact_message_show', methods: ['GET'])]
    public function show($slug): Response
    {
        $contactMessage = $this->contactMessageRepository->findOneBy([
            'slug' => $slug
        ]);

        return $this->render('contact_message/show.html.twig', [
            'contact_message' => $contactMessage,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_contact_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $slug, EntityManagerInterface $entityManager): Response
    {
        $contactMessage = $this->contactMessageRepository->findOneBy([
            'slug' => $slug
        ]);

        $form = $this->createForm(ContactMessage1Type::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contact_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contact_message/edit.html.twig', [
            'contact_message' => $contactMessage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contact_message_delete', methods: ['POST'])]
    public function delete(Request $request, ContactMessage $contactMessage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contactMessage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($contactMessage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contact_message_index', [], Response::HTTP_SEE_OTHER);
    }
}
