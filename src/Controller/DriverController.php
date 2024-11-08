<?php

namespace App\Controller;

use App\Entity\Driver;
use DateTimeImmutable;
use App\Form\DriverType;
use App\Repository\CarRepository;
use App\Repository\CompagnyRepository;
use App\Repository\DriverRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user/driver')]
class DriverController extends AbstractController
{
    private $driverRepository;
    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    #[Route('/', name: 'app_driver_index', methods: ['GET'])]
    public function index(): Response
    {

        return $this->render('driver/index.html.twig', [
            'drivers' => $this->driverRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_driver_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        PictureService $pictureService,
        EntityManagerInterface $entityManager,
        SluggerInterface $sluggerInterface,
        CarRepository $carRepository,
        CompagnyRepository $compagnyRepository
    ): Response {
        $compagny = $compagnyRepository->findBy(['user' => $this->getUser()]);

        $cars = $carRepository->findBy(['user' => $this->getUser()]);

        if (null == $cars) {
            $this->addFlash('warning', 'Pas de vehicule dans la base de donnees!!');
            return $this->redirectToRoute('app_driver_index');
        }

        if (null == $compagny) {
            $this->addFlash('warning', 'Pas de compagny dans la base de donnees!!');
            return $this->redirectToRoute('app_driver_index');
        }

        $driver = new Driver();
        $form = $this->createForm(DriverType::class, $driver);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            if ($request->get('car')) {
                $car = $carRepository->find($request->get('car'));
            }
            //si image est envoyé, on traite l'enregistrement
            if ($form->get('photoImage')->getData()) {
                //on recupere le nom image une fois enregister
                $filename = $pictureService->add($form, $driver);
                $driver->setPhoto($filename);
            } else {
                $driver->setPhoto('driver_default.webp');
            }
            //on stock les information de car, ca evite que user change d'infos en cours utilisation
            $car_array = [
                'brand' => $car->getBrand(),
                'model' => $car->getModel(),
                'licensePlate' => $car->getLicensePlate()
            ];

            $slug = strtolower($sluggerInterface->slug($driver->getName() . uniqid()));
            $driver->setSlug($slug)
                ->setVisible(0)
                ->setCreateAt(new DateTimeImmutable())
                ->setUpdateAt(new DateTimeImmutable())
                ->setUser($this->getUser())
                ->setUserId($this->getUser()->getId())
                ->setCar($car_array)
            ;

            $entityManager->persist($driver);
            $entityManager->flush();

            return $this->redirectToRoute('app_driver_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('driver/new.html.twig', [
            'driver' => $driver,
            'form' => $form,
            'cars' => $cars
        ]);
    }

    #[Route('/{slug}', name: 'app_driver_show', methods: ['GET'])]
    public function show($slug): Response
    {
        $driver = $this->driverRepository->findOneBy(['slug' => $slug, 'user' => $this->getUser()]);

        //twig affichage
        $visibility = "En cour de validation";

        if ($driver->isVisible()) {
            $visibility = "YES";
        }

        return $this->render('driver/show.html.twig', [
            'driver' => $driver,
            'visibility' => $visibility
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_driver_edit', methods: ['GET', 'POST'])]
    public function edit($slug, Request $request, PictureService $pictureService, EntityManagerInterface $entityManager, CarRepository $carRepository): Response
    {
        $cars = $carRepository->findBy(['user' => $this->getUser()]);
        $driver = $this->driverRepository->findOneBy(['slug' => $slug, 'user' => $this->getUser()]);
        //etat de validation dans twig
        $status = "Encours de validation..";
        if ($driver->isVisible()) {
            $status = "Validé";
        }

        $form = $this->createForm(DriverType::class, $driver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($request->get('car')) {
                $car = $carRepository->find($request->get('car'));
                //on stock les information de car, ca evite que user change d'infos en cours utilisation

                $driver->setCar([
                    'brand' => $car->getBrand(),
                    'model' => $car->getModel(),
                    'licensePlate' => $car->getLicensePlate()
                ]);
            }
            //si image est envoyé, on traite l'enregistrement
            if ($form->get('photoImage')->getData()) {
                //on recupere le nom image une fois enregister
                $filename = $pictureService->add($form, $driver);
                $driver->setPhoto($filename);
            }


            $driver->setUpdateAt(new DateTimeImmutable());

            //demande de validation moderateur, car changement de donnees
            $driver->setVisible(false);

            $entityManager->persist($driver);
            $entityManager->flush();

            return $this->redirectToRoute('app_driver_show', ['slug' => $driver->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('driver/edit.html.twig', [
            'driver' => $driver,
            'form' => $form,
            'status' => $status,
            'cars' => $cars
        ]);
    }

    #[Route('/{id}', name: 'app_driver_delete', methods: ['POST'])]
    public function delete(Request $request, Driver $driver, EntityManagerInterface $entityManager, PictureService $pictureService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $driver->getId(), $request->getPayload()->getString('_token'))) {
            //on supprime les photos
            $pictureService->delete($driver->getPhoto());

            $entityManager->remove($driver);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_driver_index', [], Response::HTTP_SEE_OTHER);
    }
}
