<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Investissement::class, mappedBy="user")
     */
    private $investissements;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $devise;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $accountTotalValue;

    /**
     * @ORM\OneToMany(targetEntity=Historical::class, mappedBy="user")
     */
    private $historicals;

    public function __construct()
    {
        $this->investissements = new ArrayCollection();
        $this->historicals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Investissement[]
     */
    public function getInvestissements(): Collection
    {
        return $this->investissements;
    }

    public function addInvestissement(Investissement $investissement): self
    {
        if (!$this->investissements->contains($investissement)) {
            $this->investissements[] = $investissement;
            $investissement->setUser($this);
        }

        return $this;
    }

    public function removeInvestissement(Investissement $investissement): self
    {
        if ($this->investissements->removeElement($investissement)) {
            // set the owning side to null (unless already changed)
            if ($investissement->getUser() === $this) {
                $investissement->setUser(null);
            }
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getAccountTotalValue(): ?float
    {
        return $this->accountTotalValue;
    }

    public function setAccountTotalValue(?float $accountTotalValue): self
    {
        $this->accountTotalValue = $accountTotalValue;

        return $this;
    }

    /**
     * @return Collection|Historical[]
     */
    public function getHistoricals(): Collection
    {
        return $this->historicals;
    }

    public function addHistorical(Historical $historical): self
    {
        if (!$this->historicals->contains($historical)) {
            $this->historicals[] = $historical;
            $historical->setUser($this);
        }

        return $this;
    }

    public function removeHistorical(Historical $historical): self
    {
        if ($this->historicals->removeElement($historical)) {
            // set the owning side to null (unless already changed)
            if ($historical->getUser() === $this) {
                $historical->setUser(null);
            }
        }

        return $this;
    }
}
