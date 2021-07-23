<?php

namespace App\Entity;

use App\Repository\CryptoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CryptoRepository::class)
 */
class Crypto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $symbol;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameId;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $valueUsd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $valueEur;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $valueBtc;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $valueEth;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getNameId(): ?string
    {
        return $this->nameId;
    }

    public function setNameId(string $nameId): self
    {
        $this->nameId = $nameId;

        return $this;
    }

    public function getValueUsd(): ?float
    {
        return $this->valueUsd;
    }

    public function setValueUsd(float $valueUsd): self
    {
        $this->valueUsd = $valueUsd;

        return $this;
    }

    public function getValueEur(): ?float
    {
        return $this->valueEur;
    }

    public function setValueEur(?float $valueEur): self
    {
        $this->valueEur = $valueEur;

        return $this;
    }

    public function getValueBtc(): ?float
    {
        return $this->valueBtc;
    }

    public function setValueBtc(?float $valueBtc): self
    {
        $this->valueBtc = $valueBtc;

        return $this;
    }

    public function getValueEth(): ?float
    {
        return $this->valueEth;
    }

    public function setValueEth(?float $valueEth): self
    {
        $this->valueEth = $valueEth;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
