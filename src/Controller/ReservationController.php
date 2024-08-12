<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\CarCategoryRepository;
use App\Repository\ReservationRepository;
use App\Repository\ServiceListeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        // dd($reservationRepository->findAll());
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $sluggerInterface,

    ): Response {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = strtolower($sluggerInterface->slug($reservation->getName() . uniqid()));

            $reference = strtoupper(str_replace(' ', '', $reservation->getName())) . date('Y') . date('m') . date('d') . date('H') . date('i') . date('s');

            $reservation->setSlug($slug)
                ->setCreateAt(new DateTimeImmutable())
                ->setReference($reference);
            // ->setCar($carType)
            // ->setService($service);
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_show', ['slug' => $reservation->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_reservation_show', methods: ['GET'])]
    public function show($slug, ReservationRepository $reservationRepository): Response
    {
        $reservation = $reservationRepository->findOneBy(['slug' => $slug]);
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(
        $slug,
        Request $request,
        EntityManagerInterface $entityManager,
        ReservationRepository $reservationRepository,
        ServiceListeRepository $serviceListeRepository,
        CarCategoryRepository $carCategoryRepository
    ): Response {
        $reservation = $reservationRepository->findOneBy(['slug' => $slug]);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $service_id = $request->get('reservation')['service'];
            // $car_id = $request->get('reservation')['car'];
            // $service = $serviceListeRepository->find($service_id)->getName();
            // $carType = $carCategoryRepository->find($car_id)->getName();
            // $reservation->setCar($carType)
            // ->setService($service);

            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_show', ['slug' => $reservation->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{slug}/confirm', name: 'app_reservation_delete', methods: ['POST'])]
    public function confirm($slug, Request $request, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository): Response
    {
        $reservation =  $reservationRepository->findOneBy(['slug' => $slug]);
        //1-on cree le fichier de commande
        //2-on informe l'admin, dispatcher
        //3-on envoi une confirmation au client




        $entityManager->persist($reservation);
        $entityManager->flush();


        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
