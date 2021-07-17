<?php

namespace App\Entity;

use App\Repository\CurrencyChangeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CurrencyChangeRepository::class)
 */
class CurrencyChange
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $currencyFrom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $currencyTo;

    /**
     * @ORM\Column(type="float")
     */
    private $rateValue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrencyFrom(): ?string
    {
        return $this->currencyFrom;
    }

    public function setCurrencyFrom(string $currencyFrom): self
    {
        $this->currencyFrom = $currencyFrom;

        return $this;
    }

    public function getCurrencyTo(): ?string
    {
        return $this->currencyTo;
    }

    public function setCurrencyTo(string $currencyTo): self
    {
        $this->currencyTo = $currencyTo;

        return $this;
    }

    public function getRateValue(): ?float
    {
        return $this->rateValue;
    }

    public function setRateValue(float $rateValue): self
    {
        $this->rateValue = $rateValue;

        return $this;
    }
}
