<?php

namespace App\Entity;

use App\Repository\RowRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RowRepository::class)
 */
class Row
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
     * @ORM\Column(type="float")
     */
    private $value;

    /**
     * @ORM\Column(type="float")
     */
    private $totalValue;

    /**
     * @ORM\Column(type="float")
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity=Investissement::class, inversedBy="rows")
     */
    private $investAttach;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $devise;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $ValueUSD;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $TotalValueUSD;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Symbol;

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

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getTotalValue(): ?float
    {
        return $this->totalValue;
    }

    public function setTotalValue(float $totalValue): self
    {
        $this->totalValue = $totalValue;

        return $this;
    }

    public function getNumber(): ?float
    {
        return $this->number;
    }

    public function setNumber(float $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getInvestAttach(): ?Investissement
    {
        return $this->investAttach;
    }

    public function setInvestAttach(?Investissement $investAttach): self
    {
        $this->investAttach = $investAttach;

        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(?string $devise): self
    {
        $this->devise = $devise;

        return $this;
    }

    public function getValueUSD(): ?float
    {
        return $this->ValueUSD;
    }

    public function setValueUSD(?float $ValueUSD): self
    {
        $this->ValueUSD = $ValueUSD;

        return $this;
    }

    public function getTotalValueUSD(): ?float
    {
        return $this->TotalValueUSD;
    }

    public function setTotalValueUSD(?float $TotalValueUSD): self
    {
        $this->TotalValueUSD = $TotalValueUSD;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->Symbol;
    }

    public function setSymbol(?string $Symbol): self
    {
        $this->Symbol = $Symbol;

        return $this;
    }
}
