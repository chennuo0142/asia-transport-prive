<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $compagny = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $departureAdress = null;

    #[ORM\Column(length: 255)]
    private ?string $arrivalAdress = null;

    #[ORM\Column]
    private ?int $nbPassager = null;

    #[ORM\Column]
    private ?int $nbBagage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numTransport = null;

    #[ORM\Column(nullable: true)]
    private ?int $driverId = null;

    #[ORM\Column(nullable: true)]
    private ?int $userId = null;

    #[ORM\Column]
    private ?bool $private = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(nullable: true)]
    private ?array $accessory = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateOperation = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $timeOperation = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?ServiceListe $service = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?CarCategory $car = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(nullable: true)]
    private ?bool $valide = false;

    #[ORM\Column(nullable: true)]
    private ?int $stage = null;

    #[ORM\Column(nullable: true)]
    private ?array $provider = null;

    #[ORM\Column(nullable: true)]
    private ?array $workflowStage = null;

    #[ORM\Column]
    private ?bool $endService = false;

    #[ORM\Column(nullable: true)]
    private ?array $workflowTimeline = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getCompagny(): ?string
    {
        return $this->compagny;
    }

    public function setCompagny(?string $compagny): static
    {
        $this->compagny = $compagny;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDepartureAdress(): ?string
    {
        return $this->departureAdress;
    }

    public function setDepartureAdress(string $departureAdress): static
    {
        $this->departureAdress = $departureAdress;

        return $this;
    }

    public function getArrivalAdress(): ?string
    {
        return $this->arrivalAdress;
    }

    public function setArrivalAdress(string $ArrivalAdress): static
    {
        $this->arrivalAdress = $ArrivalAdress;

        return $this;
    }

    public function getNbPassager(): ?int
    {
        return $this->nbPassager;
    }

    public function setNbPassager(int $nbPassager): static
    {
        $this->nbPassager = $nbPassager;

        return $this;
    }

    public function getNbBagage(): ?int
    {
        return $this->nbBagage;
    }

    public function setNbBagage(int $nbBagage): static
    {
        $this->nbBagage = $nbBagage;

        return $this;
    }

    public function getNumTransport(): ?string
    {
        return $this->numTransport;
    }

    public function setNumTransport(?string $numTransport): static
    {
        $this->numTransport = $numTransport;

        return $this;
    }

    public function getDriverId(): ?int
    {
        return $this->driverId;
    }

    public function setDriverId(?int $driverId): static
    {
        $this->driverId = $driverId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function isPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): static
    {
        $this->private = $private;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getAccessory(): ?array
    {
        return $this->accessory;
    }

    public function setAccessory(?array $accessory): static
    {
        $this->accessory = $accessory;

        return $this;
    }

    public function getDateOperation(): ?\DateTimeInterface
    {
        return $this->dateOperation;
    }

    public function setDateOperation(\DateTimeInterface $dateOperation): static
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    public function getTimeOperation(): ?\DateTimeInterface
    {
        return $this->timeOperation;
    }

    public function setTimeOperation(\DateTimeInterface $timeOperation): static
    {
        $this->timeOperation = $timeOperation;

        return $this;
    }

    public function getService(): ?ServiceListe
    {
        return $this->service;
    }

    public function setService(?ServiceListe $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getCar(): ?CarCategory
    {
        return $this->car;
    }

    public function setCar(?CarCategory $car): static
    {
        $this->car = $car;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(?bool $valide): static
    {
        $this->valide = $valide;

        return $this;
    }

    public function getStage(): ?int
    {
        return $this->stage;
    }

    public function setStage(?int $stage): static
    {
        $this->stage = $stage;

        return $this;
    }

    public function getProvider(): ?array
    {
        return $this->provider;
    }

    public function setProvider(?array $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    public function getWorkflowStage(): ?array
    {
        return $this->workflowStage;
    }

    public function setWorkflowStage(?array $workflowStage): static
    {
        $this->workflowStage = $workflowStage;

        return $this;
    }

    public function isEndService(): ?bool
    {
        return $this->endService;
    }

    public function setEndService(bool $endService): static
    {
        $this->endService = $endService;

        return $this;
    }

    public function getWorkflowTimeline(): ?array
    {
        return $this->workflowTimeline;
    }

    public function setWorkflowTimeline(?array $workflowTimeline): static
    {
        $this->workflowTimeline = $workflowTimeline;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }
}
