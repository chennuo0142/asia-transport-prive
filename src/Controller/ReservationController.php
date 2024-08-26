<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\ReservationArchive;
use App\Form\ReservationType;
use App\Repository\CarCategoryRepository;
use App\Repository\DriverRepository;
use App\Repository\ReservationRepository;
use App\Repository\ServiceListeRepository;
use App\Service\MailerService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        // dd($reservationRepository->findAll());
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new/{slug}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(
        $slug = null,
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $sluggerInterface,
        DriverRepository $driverRepository

    ): Response {
        $reservation = new Reservation();
        $compagny = null;
        $driver = null;

        if ($slug) {
            $driver = $driverRepository->findOneBy(['slug' => $slug]);
        }

        if ($driver) {
            $compagny = $driver->getUser()->getCompagny()->getName();
            $reservation->setDriverId($driver->getId())
                ->setUserId($this->getUser()->getId())
                ->setPrivate(true);
        }
        dump($slug);
        dump($driver);
        dump($compagny);
        dump($reservation);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = strtolower($sluggerInterface->slug($reservation->getName() . uniqid()));

            $reference = strtoupper(str_replace(' ', '', $reservation->getName())) . date('Y') . date('m') . date('d') . date('H') . date('i') . date('s');

            $reservation->setSlug($slug)
                ->setCreateAt(new DateTimeImmutable())
                ->setReference($reference)
                ->setStage(1)
                ->setWorkflowStage(array("stage" => 0, "status" => "En attente"));
            // ->setCar($carType)
            // ->setService($service);
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_show', ['slug' => $reservation->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'driver' => $driver,
            'compagny' => $compagny
        ]);
    }

    #[Route('/{slug}', name: 'app_reservation_show', methods: ['GET'])]
    public function show($slug, ReservationRepository $reservationRepository): Response
    {
        $reservation = $reservationRepository->findOneBy(['slug' => $slug]);
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(
        $slug,
        Request $request,
        EntityManagerInterface $entityManager,
        ReservationRepository $reservationRepository,

    ): Response {
        $reservation = $reservationRepository->findOneBy(['slug' => $slug]);
        //si la reservation est deja valider par public user, edition non possible
        if ($reservation->isValide()) {
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_show', ['slug' => $reservation->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/confirm/{slug}', name: 'app_reservation_confirm', methods: ['GET'])]
    public function confirm($slug, Request $request, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository, MailerService $mailerService): Response
    {
        $reservation =  $reservationRepository->findOneBy(['slug' => $slug]);

        //1-on cree le fichier de commande
        //2-on informe l'admin, dispatcher
        //3-on envoi une confirmation au client
        dump($reservation);

        if ($reservation->isValide()) {
            $this->addFlash('success', 'Votre reservation a déja été envoyer!');
            return $this->redirectToRoute('app_home');
        }
        $reservation->setValide(true);

        $entityManager->persist($reservation);
        $entityManager->flush();
        //on envoi un email a client et a admin
        $from = "booking@paris-prestige-transfert.fr";
        //on recupere email du client
        $to = $reservation->getEmail();
        $subject = "Confirmation de votre demande de reservation";
        $template = "confirmation_reservation";
        $context = ([
            'reservation' => $reservation
        ]);

        $mailerService->send($from, $to, $subject, $template, $context);

        return $this->render('reservation/confirmation.html.twig', [
            'reservation' => $reservation,

        ]);
    }
}
