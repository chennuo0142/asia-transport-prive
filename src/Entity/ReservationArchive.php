<?php

namespace App\Entity;

use App\Repository\ReservationArchiveRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationArchiveRepository::class)]
class ReservationArchive
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column]
    private array $reservation = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getReservation(): array
    {
        return $this->reservation;
    }

    public function setReservation(array $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }
}
