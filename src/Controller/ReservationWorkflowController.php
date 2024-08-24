<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\WorkflowType;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/workflow')]
class ReservationWorkflowController extends AbstractController
{
    #[Route('/reservation/index', name: 'app_reservation_workflow')]
    public function index(ReservationRepository $reservationRepository, TokenStorageInterface $tokenStorageInterface): Response
    {
        // dd($tokenStorageInterface->getToken()->getUser());
        return $this->render('reservation_workflow/index.html.twig', [
            'reservations' => $reservationRepository->findBy([
                'userId' => $this->getUser(),
                'endService' => false
            ]),
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
    public function workflow($slug, ReservationService $reservationService): Response
    {

        return $this->render('reservation_workflow/workflow.html.twig', [
            'reservation' => $reservationService->workflow_star($slug),

        ]);
    }
    #[Route('/reservation/show/end-service', name: 'app_reservation_workflow_show_end_service', methods: ['GET'])]
    public function show_end_service(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findBy([
            'userId' => $this->getUser(),
            'endService' => true
        ]);
        dump($reservations);
        return $this->render('reservation_workflow/show.endService.html.twig', [
            'reservations' => $reservations,
        ]);
    }
}
