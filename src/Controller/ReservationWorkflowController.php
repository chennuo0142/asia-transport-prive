<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\WorkflowType;
use App\Repository\ReservationRepository;
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
    public function workflow($slug, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager): Response
    {

        $reservation = $reservationRepository->findOneBy(['slug' => $slug]);
        dump($reservation);
        if ($reservation->getStage() != 3) {
            //on passe stage en etape 3
            $reservation->setStage(3)
                ->setWorkflowStage(
                    array(
                        "stage" => 1,
                        "status" => "En route vers l'adresse de prise en charge"
                    )
                );
        }

        //on passe workflow en etape 2, status en Je suis arriver      
        if ($reservation->getWorkflowStage()['stage'] == 1) {
            $reservation->setWorkflowStage(
                array(
                    "stage" => 2,
                    "status" => "Je suis arriver"
                )
            );
        } elseif ($reservation->getWorkflowStage()['stage'] == 2) {

            $reservation->setWorkflowStage(
                array(
                    "stage" => 3,
                    "status" => "client a bord"
                )
            );
        } elseif ($reservation->getWorkflowStage()['stage'] == 3) {
            $reservation->setWorkflowStage(
                array(
                    "stage" => 4,
                    "status" => "arriver a destination"
                )
            );
        } elseif ($reservation->getWorkflowStage()['stage'] == 4) {
            $reservation->setWorkflowStage(
                array(
                    "stage" => 5,
                    "status" => "fin de service"
                )
            )
                ->setEndService(true);
        }


        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->render('reservation_workflow/workflow.html.twig', [
            'reservation' => $reservation,

        ]);
    }
}
