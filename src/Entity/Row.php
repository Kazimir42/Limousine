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
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity=Investissement::class, inversedBy="rows")
     */
    private $investAttach;

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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
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
}
