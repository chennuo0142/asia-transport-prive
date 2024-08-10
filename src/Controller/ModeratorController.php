<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Driver;
use App\Repository\CarRepository;
use App\Repository\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/moderator')]
class ModeratorController extends AbstractController
{

    #[Route('/', name: 'app_moderator')]
    public function index(DriverRepository $driverRepository, CarRepository $carRepository): Response
    {
        //on recupere tous les drivers avec visibility false
        $drivers = $driverRepository->findBy(['isVisible' => false]);

        // on recupere tous les car avec visible false
        $cars = $carRepository->findBy(['visible' => false]);

        dump(count($cars));
        return $this->render('moderator/index.html.twig', [
            'drivers' => $drivers,
            'cars' => $cars,
            'nbr_cars' => count($cars),
            'nbr_drivers' => count($drivers)
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

    #[Route('/car/show/{slug}', name: 'app_moderator_car_show')]
    public function car_show($slug, CarRepository $carRepository): Response
    {
        //on recupere tous les drivers avec visibility false
        $car = $carRepository->findOneBy(['slug' => $slug]);

        //twig affichage
        $visible = "NON";

        if ($car->isVisible()) {
            $visible = "YES";
        }

        return $this->render('moderator/car_show.html.twig', [
            'car' => $car,
            'visible' => $visible
        ]);
    }

    #[Route('/car/validation/{id}', name: 'app_moderator_car_validation')]
    public function car_validator(Car $car, EntityManagerInterface $entityManager): Response
    {
        $car->setVisible(true);

        $entityManager->persist($car);
        $entityManager->flush();

        $this->addFlash('success', 'Les modification sont enregistrer avec succees!');


        return $this->redirectToRoute('app_moderator');;
    }
}
