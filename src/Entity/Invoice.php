<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ref = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?int $user = null;

    #[ORM\Column]
    private array $company = [];

    #[ORM\Column]
    private array $customer = [];

    #[ORM\Column]
    private array $product = [];

    #[ORM\Column]
    private array $total = [];

    #[ORM\Column(nullable: true)]
    private ?array $bank = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $creatAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $opDate = null;

    #[ORM\Column(nullable: true)]
    private ?bool $showTvaText = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $invoiceDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

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

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCompany(): array
    {
        return $this->company;
    }

    public function setCompany(array $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getCustomer(): array
    {
        return $this->customer;
    }

    public function setCustomer(array $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getProduct(): array
    {
        return $this->product;
    }

    public function setProduct(array $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getTotal(): array
    {
        return $this->total;
    }

    public function setTotal(array $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getBank(): ?array
    {
        return $this->bank;
    }

    public function setBank(?array $bank): static
    {
        $this->bank = $bank;

        return $this;
    }

    public function getCreatAt(): ?\DateTimeImmutable
    {
        return $this->creatAt;
    }

    public function setCreatAt(\DateTimeImmutable $creatAt): static
    {
        $this->creatAt = $creatAt;

        return $this;
    }

    public function getOpDate(): ?\DateTimeInterface
    {
        return $this->opDate;
    }

    public function setOpDate(?\DateTimeInterface $opDate): static
    {
        $this->opDate = $opDate;

        return $this;
    }

    public function isShowTvaText(): ?bool
    {
        return $this->showTvaText;
    }

    public function setShowTvaText(?bool $showTvaText): static
    {
        $this->showTvaText = $showTvaText;

        return $this;
    }

    public function getInvoiceDate(): ?\DateTimeInterface
    {
        return $this->invoiceDate;
    }

    public function setInvoiceDate(?\DateTimeInterface $invoiceDate): static
    {
        $this->invoiceDate = $invoiceDate;

        return $this;
    }
}
