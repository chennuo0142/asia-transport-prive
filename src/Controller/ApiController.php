<?php

namespace App\Controller;

use DateTime;
use App\Entity\Client;
use DateTimeImmutable;
use App\Entity\Invoice;
use App\Repository\ClientRepository;
use App\Repository\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/api/driver', name: 'app_api_driver', methods: 'GET')]
    public function driver(DriverRepository $driverRepository): Response
    {
        $drivers = $driverRepository->findAll();

        return $this->json($drivers, 200, [], [
            'groups' => ['post:read']
        ]);
    }

    #[Route('/api/customer/{id}', name: 'app_api_customer', methods: 'GET')]
    public function customer(Client $client): Response
    {

        return $this->json($client, 200, [], [
            'groups' => ['post:read']
        ]);
    }

    #[Route('/api/invoice/post', name: 'app_api_invoice_post', methods: 'POST')]
    public function invoice_post(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $jsonRecu = $request->getContent();
        $invoice = $serializer->deserialize($jsonRecu, Invoice::class, 'json');
        $invoice
            ->setCreatAt(new \DateTimeImmutable())
            ->setOpDate(new \DateTime())
            ->setInvoiceDate(new \DateTime())
            ->setShowTvaText(true);

        $entityManager->persist($invoice);
        $entityManager->flush();

        return $this->json([
            $invoice,
            201,
            'status' => "ok",
            []
        ]);
    }
}
