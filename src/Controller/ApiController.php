<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\DriverRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/api/driver', name: 'app_api_driver', methods: 'GET')]
    public function driver(DriverRepository $driverRepository): Response
    {
        $drivers = $driverRepository->findAll();
        sleep(2);
        return $this->json($drivers, 200, [], [
            'groups' => ['post:read']
        ]);
    }

    #[Route('/api/customer/{id}', name: 'app_api_customer', methods: 'GET')]
    public function customer(Client $client): Response
    {

        sleep(2);
        return $this->json($client, 200, [], [
            'groups' => ['post:read']
        ]);
    }
}
