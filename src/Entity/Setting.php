<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'setting', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $showBank = null;

    #[ORM\Column(nullable: true)]
    private ?bool $showNoTvaText = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $noTvaText = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $latePaymentText = null;

    #[ORM\Column(nullable: true)]
    private ?bool $showLatePaymentText = null;

    #[ORM\Column(nullable: true)]
    private ?bool $showDateOperation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isShowBank(): ?bool
    {
        return $this->showBank;
    }

    public function setShowBank(bool $showBank): static
    {
        $this->showBank = $showBank;

        return $this;
    }

    public function isShowNoTvaText(): ?bool
    {
        return $this->showNoTvaText;
    }

    public function setShowNoTvaText(bool $shwoNoTvaText): static
    {
        $this->showNoTvaText = $shwoNoTvaText;

        return $this;
    }

    public function getNoTvaText(): ?string
    {
        return $this->noTvaText;
    }

    public function setNoTvaText(?string $noTvaText): static
    {
        $this->noTvaText = $noTvaText;

        return $this;
    }

    public function getLatePaymentText(): ?string
    {
        return $this->latePaymentText;
    }

    public function setLatePaymentText(?string $latePaymentText): static
    {
        $this->latePaymentText = $latePaymentText;

        return $this;
    }

    public function isShowLatePaymentText(): ?bool
    {
        return $this->showLatePaymentText;
    }

    public function setShowLatePaymentText(bool $showLatePaymentText): static
    {
        $this->showLatePaymentText = $showLatePaymentText;

        return $this;
    }

    public function isShowDateOperation(): ?bool
    {
        return $this->showDateOperation;
    }

    public function setShowDateOperation(bool $showDateOperation): static
    {
        $this->showDateOperation = $showDateOperation;

        return $this;
    }
}
