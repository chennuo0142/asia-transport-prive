<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\Compagny;
use App\Form\InvoiceType;
use App\Service\MakePdfService;
use Symfony\Component\Mime\Email;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BankAccountRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/gestion')]
class FactureController extends AbstractController
{
    #[Route('/facture', name: 'app_facture_index')]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        $invoices = $invoiceRepository->findBy([
            'user' => $this->getUser()
        ]);
        dump($invoices);
        return $this->render('facture/index.html.twig', [
            'invoices' => $invoices
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
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $invoice = new Invoice();
        $user = $this->getUser();
        dump($user);
        $userCompany = $user->getCompagny();
        $userCustomers = $user->getClients();
        $userArticles = $user->getArticles();
        dump($userArticles);
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
            'userArticles' => $userArticles


        ]);
    }

    #[Route('/facture/show/{slug}', name: 'app_facture_show')]
    public function show($slug, InvoiceRepository $invoiceRepository, BankAccountRepository $bankAccountRepository): Response
    {
        $user = $this->getUser();
        $invoice = $invoiceRepository->findOneBy([
            'slug' => $slug
        ]);
        $bankAccount = $bankAccountRepository->findOneBy(['user' => $user]);
        if ($bankAccount == null) {
            return false;
        }
        dump($bankAccount);
        dump($invoice);
        $setting = $user->getSetting();

        dump($setting);


        return $this->render('facture/show.html.twig', [
            'invoice' => $invoice,
            'company' => $user->getCompagny(),
            'setting' => $user->getSetting(),
            'bankAccount' => $bankAccount

        ]);
    }

    #[Route('/facture/pdf/{slug}', name: 'app_facture_pdf')]
    public function pdf($slug, InvoiceRepository $invoiceRepository, BankAccountRepository $bankAccountRepository, MakePdfService $makePdfService)
    {
        $user = $this->getUser();
        $invoice = $invoiceRepository->findOneBy([
            'slug' => $slug
        ]);
        $bankAccount = $bankAccountRepository->findOneBy(['user' => $user]);
        if ($bankAccount == null) {
            return false;
        }
        dump($bankAccount);
        dump($invoice);
        $setting = $user->getSetting();

        dump($setting);
        $makePdfService->makeFacturePdf($invoice, $user, $bankAccount);
    }
}
