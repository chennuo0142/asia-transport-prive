<?php

namespace App\Controller;

use App\Entity\Driver;
use App\Repository\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/moderator')]
class ModeratorController extends AbstractController
{

    #[Route('/', name: 'app_moderator')]
    public function index(DriverRepository $driverRepository): Response
    {
        //on recupere tous les drivers avec visibility false
        $drivers = $driverRepository->findBy(['isVisible' => false]);

        dump($drivers);
        return $this->render('moderator/index.html.twig', [
            'drivers' => $drivers,
        ]);
    }

    #[Route('/driver/show/{slug}', name: 'app_moderator_driver_show')]
    public function driver_show($slug, DriverRepository $driverRepository): Response
    {
        //on recupere tous les drivers avec visibility false
        $driver = $driverRepository->findOneBy(['slug' => $slug]);

        //twig affichage
        $visibility = "NON";

        if ($driver->isVisible()) {
            $visibility = "YES";
        }

        return $this->render('moderator/driver_show.html.twig', [
            'driver' => $driver,
            'visibility' => $visibility
        ]);
    }

    #[Route('/driver/validation/{id}', name: 'app_moderator_driver_validation')]
    public function driver_validator(Driver $driver, EntityManagerInterface $entityManager): Response
    {
        $driver->setVisible(true);

        $entityManager->persist($driver);
        $entityManager->flush();

        $this->addFlash('success', 'Les modification sont enregistrer avec succees!');


        return $this->redirectToRoute('app_moderator');;
    }
}
