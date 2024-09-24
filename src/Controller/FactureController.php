<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gestion')]
class FactureController extends AbstractController
{
    #[Route('/facture', name: 'app_facture')]
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
}
