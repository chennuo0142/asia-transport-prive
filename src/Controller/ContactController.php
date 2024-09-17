<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactMessageType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface, Request $request): Response
    {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {




            $slug = strtolower($sluggerInterface->slug($contactMessage->getName() . uniqid()));
            $contactMessage->setSlug($slug)
                ->setCreateAt(new DateTimeImmutable())
            ;

            $entityManager->persist($contactMessage);
            $entityManager->flush();

            $this->addFlash('success', 'Votre message a bien ete envoye.');

            return $this->redirectToRoute('app_contact', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
