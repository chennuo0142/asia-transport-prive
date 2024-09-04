<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Driver;
use App\Entity\Message;
use App\Form\RefuseValidationType;
use App\Repository\CarRepository;
use App\Repository\DriverRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function car_show($slug, CarRepository $carRepository, Request $request, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface): Response
    {
        $message = new Message;
        //on recupere tous les drivers avec visibility false
        $car = $carRepository->findOneBy(['slug' => $slug]);

        $form = $this->createForm(RefuseValidationType::class);
        //twig affichage
        $visible = "NON";

        if ($car->isVisible()) {
            $visible = "YES";
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $slug = strtolower($sluggerInterface->slug($request->get('refuse_validation')['motif'] . uniqid()));

            //validation refuser, on envoi un message interne à driver et un email

            $message->setUser($car->getUser()->getId())
                ->setObjet($request->get('refuse_validation')['motif'])
                ->setMessage($request->get('refuse_validation')['message'])
                ->setSlug($slug)
                ->setCategory('Gestion')
                ->setCreateAt(new DateTimeImmutable());


            $entityManager->persist($message);
            $entityManager->flush();

            //on envoi un email




            return $this->redirectToRoute('app_moderator', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('moderator/car_show.html.twig', [
            'car' => $car,
            'visible' => $visible,
            'form' => $form
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
