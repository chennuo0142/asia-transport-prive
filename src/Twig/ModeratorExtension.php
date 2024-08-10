<?php

namespace App\Twig;

use App\Entity\Car;
use App\Entity\Driver;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;

class ModeratorExtension extends AbstractExtension
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFunctions(): array
    {
        return [new TwigFunction('moderation', [$this, 'getTotalModeration'])];
    }

    public function getTotalModeration(): int
    {
        $total_drivers = $this->entityManager->getRepository(Driver::class)->findBy(['isVisible' => false]);
        $total_cars = $this->entityManager->getRepository(Car::class)->findBy(['visible' => false]);
        $total = count($total_cars) + count($total_drivers);

        return $total;
    }
}
