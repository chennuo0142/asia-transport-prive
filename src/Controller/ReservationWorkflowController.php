<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/workflow')]
class ReservationWorkflowController extends AbstractController
{
    #[Route('/reservation/index', name: 'app_reservation_workflow')]
    public function index(ReservationRepository $reservationRepository): Response
    {

        return $this->render('reservation_workflow/index.html.twig', [
            'reservations' => $reservationRepository->findBy(['userId' => $this->getUser()]),
        ]);
    }

    #[Route('/reservation/{slug}/show', name: 'app_reservation_workflow_show', methods: ['GET'])]
    public function show($slug, ReservationRepository $reservationRepository): Response
    {
        $reservation = $reservationRepository->findOneBy(['slug' => $slug]);
        dump($reservation);
        return $this->render('reservation_workflow/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/reservation/{slug}/star', name: 'app_reservation_workflow_star', methods: ['GET'])]
    public function star($slug, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager): Response
    {
        $reservation = $reservationRepository->findOneBy(['slug' => $slug]);
        dump($reservation);
        if ($reservation) {
            //on pas stage en etape 3
            $reservation->setStage(3);
        }
        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->render('reservation_workflow/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
