<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\InvoiceRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/gestion')]
class FactureController extends AbstractController
{
    #[Route('/facture', name: 'app_facture_index')]
    public function index(): Response
    {
        return $this->render('facture/index.html.twig', [
            'controller_name' => 'FactureController',
        ]);
    }

    #[Route('/facture/reservation/{slug}', name: 'app_facture_reservation')]
    public function facture_reservation($slug, ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();

        $userCompagny = $user->getCompagny();
        if ($userCompagny == null) {
            $this->addFlash('warning', 'Les informatins sur la societe introuvable!');
            return $this->redirectToRoute('app_reservation_workflow_show');
        }
        dump($userCompagny);
        $reservation = $reservationRepository->findOneBy([
            'slug' => $slug
        ]);
        if ($reservation) {
        }
        dump($reservation);

        return $this->render('facture/reservation.html.twig', [
            'reservation' => $reservation,
            'userCompagny' => $userCompagny
        ]);
    }

    #[Route('/facture/new', name: 'app_facture_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invoice = new Invoice();
        $user = $this->getUser();
        dump($user);
        $userCompany = $user->getCompagny();
        $userCustomers = $user->getClients();
        dump($userCustomers[0]);

        if ($userCompany == null) {
            $this->addFlash('warning', 'Les informatins sur la societe introuvable!');
            return $this->redirectToRoute('app_facture_index');
        }

        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($request);
            $articles = $request->request->get("articles");

            $articles_decode = json_decode($articles);

            dump($articles_decode);
            foreach ($articles_decode as $article) {
                dump($article);
            }
            dd(json_decode($articles));
            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/new.html.twig', [
            'form' => $form,
            'company' => $userCompany,
            'userCustomers' => $userCustomers,


        ]);
    }

    #[Route('/facture/show/{slug}', name: 'app_facture_show')]
    public function show($slug, InvoiceRepository $invoiceRepository): Response
    {
        $invoice = $invoiceRepository->findBy([
            'slug' => $slug
        ]);
        dd($invoice);
        return $this->render('facture/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }


    #[Route('/facture/{id}/show', name: 'app_facture_afficher')]
    public function afficher(Invoice $invoice): Response
    {

        dd($invoice);
        return $this->render('facture/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }
}
