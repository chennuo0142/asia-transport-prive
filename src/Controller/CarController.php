<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Service\PictureService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/gestion/car')]
class CarController extends AbstractController
{
    private $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    #[Route('/', name: 'app_car_index', methods: ['GET'])]
    public function index(CarRepository $carRepository): Response
    {
        return $this->render('car/index.html.twig', [
            'cars' => $carRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_car_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PictureService $pictureService, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('photoImage')->getData()) {
                //on recupere le nom image une fois enregister
                $filename = $pictureService->add($form, $car);
                $car->setPhoto($filename);
            } else {
                $car->setPhoto('car_default.webp');
            }

            $slug = strtolower($sluggerInterface->slug($car->getLicensePlate() . uniqid()));

            $car->setUser($this->getUser())
                ->setCreateAt(new DateTimeImmutable())
                ->setUpdateAt(new DateTimeImmutable())
                ->setSlug($slug);

            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_car_show', methods: ['GET'])]
    public function show($slug): Response
    {
        $car = $this->carRepository->findOneBy(['slug' => $slug]);
        //twig affichage
        $visible = "En cour de validation";

        if ($car->isVisible()) {
            $visible = "YES";
        }
        return $this->render('car/show.html.twig', [
            'car' => $car,
            'visible' => $visible
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_car_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $slug, PictureService $pictureService, EntityManagerInterface $entityManager): Response
    {
        $maxImagesGalery = 4;
        $car = $this->carRepository->findOneBy(['slug' => $slug]);
        //on recupere la galery dans car, si vide on crer un nouvaux
        $galery = $car->getGalery();
        if ($galery == null) {
            $galery = [];
        }

        $nbr_images_in_galery = count($galery);

        $place_libre = $maxImagesGalery - $nbr_images_in_galery;



        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //si les images de galery sont envoyés
            if ($form->get('galery')->getData()) {

                $images = $form->get('galery')->getData();
                dump($images);
                dump(count($images));
                //on verifier la quantite image envoyé avec le max autoriser
                if (count($images) <= $place_libre) {

                    foreach ($images as $image) {
                        //on verifie si le fichier envoyer est bien une image
                        $type = $image->getClientMimeType();

                        if ($type == "image/jpeg" || $type == "image/webp" || $type == "image/png") {

                            //si le fichier est bien une image, on l'enregistre dans la galery
                            //1 on crer le nom unique
                            $filename = $pictureService->add_galery($image, $car);

                            //on ajout le nom image dans car galery
                            array_push($galery, $filename);
                        } else {
                            throw new Exception("Une ou plusieur format d'image incorrect");
                        }
                    }
                    $car->setGalery($galery);
                } else {
                    throw new Exception("Nombre images max atteint!");
                }
            }


            //si image est envoyé, on traite l'enregistrement
            if ($form->get('photoImage')->getData()) {
                //on recupere le nom image une fois enregister
                $filename = $pictureService->add($form, $car);
                $car->setPhoto($filename);
            }

            $car->setUpdateAt(new DateTimeImmutable())
                ->setVisible(false);
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_car_delete', methods: ['POST'])]
    public function delete(Request $request, Car $car, PictureService $pictureService, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $car->getId(), $request->getPayload()->getString('_token'))) {
            $pictureService->delete($car->getPhoto());
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{item}/{slug}/delete', name: 'app_car_galery_delete', methods: ['GET'])]
    public function delete_picture($item, $slug, PictureService $pictureService, CarRepository $carRepository, EntityManagerInterface $entityManager): Response
    {

        $car = $carRepository->findOneBy(['slug' => $slug]);
        //on supprime les fichier sur disque
        $pictureService->delete($item);
        //on suprime le fichier sur la base de donner
        $galery = $car->getGalery();

        //on verifi si le fichier existe dans la galery
        if (in_array($item, $galery)) {
            //on supprime le nom dans galery
            //on retrouve la cle item et supprime
            $key = array_search($item, $galery);
            unset($galery[$key]);

            //on effectuer une mise à jour la galery de car
            $car->setGalery($galery);
        }

        //on enregistre le changement dans la base
        $entityManager->persist($car);
        $entityManager->flush();



        return $this->redirectToRoute('app_car_edit', ['slug' => $slug], Response::HTTP_SEE_OTHER);
    }
}
