<?php

namespace App\Service;

use DateTimeImmutable;
use App\Entity\Reservation;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService
{
    private $mailerService;
    private $entityManager;
    private $status  = [
        1 => "En route vers l'adresse de prise en charge",
        2 => "Je suis arriver",
        3 => "Client à bord",
        4 => "Arriver à destination",
        5 => "Fin de service"
    ];

    public function __construct(EntityManagerInterface $entityManager, MailerService $mailerService)
    {
        $this->entityManager = $entityManager;
        $this->mailerService = $mailerService;
    }

    //return la reservation
    public function workflow_star($slug)
    {
        //on defini zone horaire
        date_default_timezone_set('Europe/Paris');
        //on recupere la reservation par son slug
        $reservation = $this->entityManager->getRepository(Reservation::class)->findOneBy(['slug' => $slug]);


        $time_line = $reservation->getWorkflowTimeline();

        if ($time_line == null) {
            $time_line = [];
        }

        //on prepare l'envoi email
        $to = $reservation->getEmail();
        $subject = "Etape de votre reservation";
        $template = "workflow_client";


        ///////////////////////////////
        if ($reservation->getStage() != 3) {

            array_push($time_line, ['stage' => 1, 'status' => $this->status[1], "date" => new DateTimeImmutable('now')]);
            //on passe stage en etape 3: driver star the service
            $reservation->setStage(3)
                ->setWorkflowStage(
                    array(
                        "stage" => 1,
                        "status" => $this->status[1]
                    )
                )
                ->setWorkflowTimeline($time_line);
        }
        //on passe workflow en etape 2, status en Je suis arriver      
        elseif ($reservation->getWorkflowStage()['stage'] == 1) {
            array_push($time_line, ['stage' => 2, 'status' => $this->status[2], "date" => new DateTimeImmutable('now')]);
            $reservation->setWorkflowStage(
                array(
                    "stage" => 2,
                    "status" => $this->status[2]
                )
            )->setWorkflowTimeline($time_line);
            ///////////////////////envoi email //////////////////////////////
            //envoi email pour informer le client le status de la reservation 
            $context = ([
                'reservation' => $reservation,
                'status' => $this->status["1"] //status-1: "En route vers l'adresse de prise en charge"
            ]);

            $this->mailerService->send($to, $subject, $template, $context);
            //////////////////////////
        } elseif ($reservation->getWorkflowStage()['stage'] == 2) {
            array_push($time_line, ['stage' => 3, 'status' => $this->status[3], "date" => new DateTimeImmutable('now')]);
            $reservation->setWorkflowStage(
                array(
                    "stage" => 3,
                    "status" => $this->status[3]
                )
            )->setWorkflowTimeline($time_line);
            ///////////////////////Envoi email ////////////////////////////////////
            //envoi email pour informer le client le status de la reservation : je suis en route
            $context = ([
                'reservation' => $reservation,
                'status' => $this->status["2"] //status-2:"Je suis arriver",
            ]);
            //////////////////////////////////////////////////////////
            $this->mailerService->send($to, $subject, $template, $context);
        } elseif ($reservation->getWorkflowStage()['stage'] == 3) {
            array_push($time_line, ['stage' => 4, 'status' => $this->status[4], "date" => new DateTimeImmutable('now')]);
            $reservation->setWorkflowStage(
                array(
                    "stage" => 4,
                    "status" => $this->status[4]
                )
            )->setWorkflowTimeline($time_line);
        } elseif ($reservation->getWorkflowStage()['stage'] == 4) {
            $reservation->setWorkflowStage(
                array(
                    "stage" => 5,
                    "status" => $this->status[5]
                )
            )
                ->setEndService(true);
        }


        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $reservation;
    }
}
