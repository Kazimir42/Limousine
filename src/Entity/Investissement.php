<?php

namespace App\Entity;

use App\Repository\InvestissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvestissementRepository::class)
 */
class Investissement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $risk;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="date")
     */
    private $lastModif;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalValue;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $devise;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="investissements")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Row::class, mappedBy="investAttach")
     */
    private $rows;

    public function __construct()
    {
        $this->rows = new ArrayCollection();
    }

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRisk(): ?string
    {
        return $this->risk;
    }

    public function setRisk(string $risk): self
    {
        $this->risk = $risk;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getLastModif(): ?\DateTimeInterface
    {
        return $this->lastModif;
    }

    public function setLastModif(\DateTimeInterface $lastModif): self
    {
        $this->lastModif = $lastModif;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): self
    {
        $this->statut = $statut;

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

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(string $devise): self
    {
        $this->devise = $devise;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Row[]
     */
    public function getRows(): Collection
    {
        return $this->rows;
    }

    public function addRow(Row $row): self
    {
        if (!$this->rows->contains($row)) {
            $this->rows[] = $row;
            $row->setInvestAttach($this);
        }

        return $this;
    }

    public function removeRow(Row $row): self
    {
        if ($this->rows->removeElement($row)) {
            // set the owning side to null (unless already changed)
            if ($row->getInvestAttach() === $this) {
                $row->setInvestAttach(null);
            }
        }

        return $this;
    }
}
