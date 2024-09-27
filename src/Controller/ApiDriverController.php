<?php

namespace App\Controller;

use App\Repository\DriverRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiDriverController extends AbstractController
{
    #[Route('/api/driver', name: 'app_api_driver', methods: 'GET')]
    public function index(DriverRepository $driverRepository): Response
    {
        $drivers = $driverRepository->findAll();
        sleep(2);
        return $this->json($drivers, 200, [], [
            'groups' => ['post:read']
        ]);
    }
}
