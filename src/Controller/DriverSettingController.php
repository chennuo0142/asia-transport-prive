<?php

namespace App\Controller;

use App\Entity\Driver;
use App\Form\DriverSettingType;
use App\Repository\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/setting')]
class DriverSettingController extends AbstractController
{
    #[Route('/driver/{slug}', name: 'app_driver_setting')]
    public function index($slug, DriverRepository $driverRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $driver = $driverRepository->findOneBy(['slug' => $slug]);
        //status visible driver
        $status = "";
        if ($driver->isVisible() == true) {
            $status = "checked";
        }
        dump($status);
        dump($driver);
        $form = $this->createForm(DriverSettingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($request->get('visible')) {
                $driver->setVisible(true);
            } else {
                $driver->setVisible(false);
            }
            $entityManager->persist($driver);
            $entityManager->flush();
            return $this->redirectToRoute('app_driver_show', ['slug' => $driver->getSlug()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('driver_setting/index.html.twig', [
            'controller_name' => 'DriverSettingController',
            'form' => $form,
            'driver' => $driver,
            'status_checked' => $status
        ]);
    }
}
