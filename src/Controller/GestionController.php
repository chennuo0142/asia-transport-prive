<?php

namespace App\Controller;

use App\Form\ReservationDispatcherType;
use App\Repository\DriverRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gestion')]
class GestionController extends AbstractController
{
    #[Route('/{stage}', name: 'app_gestion')]
    public function index($stage, ReservationRepository $reservationRepository): Response
    {

        //stage 1 return reservation en attente de driver
        //stage 2 return reservations avec chaffeur et la societe prestataire
        //stage 3 retrun les reservation avec deux status('service en cours' ou 'service terminé')
        if ($stage == 1) {

            $reservations = $reservationRepository->findBy([
                'private' => false,
                'driverId' => null,
                'userId' => null,
                'stage' => 1
            ]);
        } elseif ($stage == 2) {
            $stage = 2;
            $reservations = $reservationRepository->findBy([
                'private' => false,
                'stage' => 2
            ]);
        } else {
            $stage = 3;
            $reservations = $reservationRepository->findBy([
                'private' => false,
                'stage' => 3
            ]);
        }


        return $this->render('gestion/index.html.twig', [
            'reservations' => $reservations,
            'total_reservation' => count($reservations),
            'stage' => $stage
        ]);
    }

    #[Route('/reservation/{slug}/show', name: 'app_gestion_reservation_show')]
    public function reservation_show($slug, ReservationRepository $reservationRepository, Request $request, EntityManagerInterface $entityManager, DriverRepository $driverRepository): Response
    {
        $driver = null;
        $compagny = null;

        $drivers = $driverRepository->findAll();
        $reservation = $reservationRepository->findOneBy(['slug' => $slug]);
        $form = $this->createForm(ReservationDispatcherType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($reservation);
            dump($request->get('driver'));
            $driver = $driverRepository->find($request->get('driver'));
            $userId = $driver->getUserId();

            //recuperer array contenant tous les informations necessaure du chauffeur dans un tableau
            //evitant les bug si user supprime les elements
            //$provider contient le user, la societe et la voiture du chauffeur
            $provider = $driver->getArray();



            //demande de validation moderateur, car changement de donnees
            $reservation->setUserId($userId)->setDriverId($request->get('driver'))->setStage(2)->setProvider($provider);

            $entityManager->persist($reservation);
            $entityManager->flush();


            return $this->redirectToRoute('app_gestion', ['stage' => 1], Response::HTTP_SEE_OTHER);
        }



        return $this->render('gestion/reservation/show.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'drivers' => $drivers,

        ]);
    }
}
