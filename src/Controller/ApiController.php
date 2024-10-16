<?php

namespace App\Controller;

use DateTime;
use App\Entity\Client;
use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Invoice;
use App\Repository\ArticleRepository;
use App\Repository\ClientRepository;
use App\Repository\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
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
    public function invoice_post(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface): Response
    {
        $user = $this->getUser();
        //1 user id
        //2 bank infos
        //3 numeros de tva

        dd('stop');
        $jsonRecu = $request->getContent();
        $invoice = $serializer->deserialize($jsonRecu, Invoice::class, 'json');

        $slug = strtolower($sluggerInterface->slug("Facture-" . uniqid()));
        $reference = strtoupper($sluggerInterface->slug($invoice->getCustomer()['firstName'] . uniqid()));
        $invoice
            ->setSlug($slug)
            ->setRef($reference)
            ->setCreatAt(new \DateTimeImmutable())
            ->setDateOperation(new \DateTime())
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

    #[Route('/api/article/{id}', name: 'app_api_article', methods: 'GET')]
    public function getArticle(Article $article): Response
    {

        return $this->json($article, 200, [], [
            'groups' => ['post:read']
        ]);
    }
}
