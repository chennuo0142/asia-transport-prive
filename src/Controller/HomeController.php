<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DriverRepository $driverRepository, EntityManagerInterface $entityManager): Response
    {
        //on recupere les driver visible
        $drivers = $driverRepository->findBy(['isVisible' => true]);
        // $user_moderators = $entityManager->getRepository(User::class)->findAllUser('ROLE_MODERATOR');

        // foreach ($user_moderators as $user) {
        //     dump($user->getEmail());
        // }
        // dd($user_moderators);
        // dump($drivers);
        return $this->render('home/index.html.twig', [
            'drivers' => $drivers,
        ]);
    }

    #[Route('/public/driver/{slug}', name: 'app_public_driver_show')]
    public function public_driver_show($slug, DriverRepository $driverRepository): Response
    {
        //on recupere les driver visible
        $driver = $driverRepository->findOneBy([
            'isVisible' => true,
            'slug' => $slug
        ]);
        dump($driver);
        return $this->render('home/public/driver_show.html.twig', [
            'driver' => $driver,
        ]);
    }
}
