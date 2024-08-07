<?php

namespace App\Controller;

use App\Entity\Driver;
use DateTimeImmutable;
use App\Form\DriverType;
use App\Form\DriverSettingType;
use App\Repository\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/driver')]
class DriverController extends AbstractController
{
    private $driverRepository;
    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    #[Route('/', name: 'app_driver_index', methods: ['GET'])]
    public function index(DriverRepository $driverRepository): Response
    {
        return $this->render('driver/index.html.twig', [
            'drivers' => $driverRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_driver_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface): Response
    {
        $driver = new Driver();
        $form = $this->createForm(DriverType::class, $driver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = strtolower($sluggerInterface->slug($driver->getName() . uniqid()));
            $driver->setSlug($slug)
                ->setVisible(0)
                ->setCreateAt(new DateTimeImmutable())
                ->setUpdateAt(new DateTimeImmutable())
                ->setUser($this->getUser())
                ->setUserId($this->getUser()->getId());

            $entityManager->persist($driver);
            $entityManager->flush();

            return $this->redirectToRoute('app_driver_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('driver/new.html.twig', [
            'driver' => $driver,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_driver_show', methods: ['GET'])]
    public function show($slug, DriverRepository $driverRepository): Response
    {
        $driver =  $driverRepository->findOneBy(['slug' => $slug]);

        return $this->render('driver/show.html.twig', [
            'driver' => $driver,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_driver_edit', methods: ['GET', 'POST'])]
    public function edit($slug, Request $request, EntityManagerInterface $entityManager): Response
    {
        $driver = $this->driverRepository->findOneBy(['slug' => $slug]);

        $form = $this->createForm(DriverType::class, $driver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $driver->setUpdateAt(new DateTimeImmutable());

            $entityManager->flush();

            return $this->redirectToRoute('app_driver_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('driver/edit.html.twig', [
            'driver' => $driver,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_driver_delete', methods: ['POST'])]
    public function delete(Request $request, Driver $driver, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $driver->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($driver);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_driver_index', [], Response::HTTP_SEE_OTHER);
    }
}
