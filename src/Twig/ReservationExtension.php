<?php

namespace App\Twig;


use Twig\TwigFunction;

use App\Entity\Reservation;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReservationExtension extends AbstractExtension
{
    private $entityManager;
    private $tokenStorage;


    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorageInterface)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorageInterface;
    }

    public function getFunctions(): array
    {
        //on cree l'extension twig, 'moderation est le nom du variable, getTotalModeration est la fonction appeler'
        return [new TwigFunction('nbrReservation', [$this, 'getTotalReservationsUser'])];
    }

    public function getTotalReservationsUser(): int
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $reservations = $this->entityManager->getRepository(Reservation::class)->findBy([
            'userId' => $user,
            'endService' => false
        ]);


        return count($reservations);
    }
}
