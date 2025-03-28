<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NOM', fields: ['nom'])]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cette adresse email.')]
#[UniqueEntity(fields: ['nom'], message: 'Il y a déjà un compte avec ce nom de réservation.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_USER = 'ROLE_USER';     
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    private ?string $confirmPassword = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $lastConnexion;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: "user")]
    private Collection $reservations;

    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: "user")]
    private Collection $comments;

    public function getId(): ?int
    {
        return $this->id;
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
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __construct()     
    {         
        $this->roles = [self::ROLE_USER];
        $this->reservations = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();

        $this->lastConnexion = new \DateTimeImmutable();
    }

    public function getLastConnexion(): ?\DateTimeImmutable
    {
        return $this->lastConnexion;
    }

    public function setLastConnexion(?\DateTimeImmutable $lastLogin): self
    {
        $this->lastConnexion = $lastLogin;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    } 

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function setReservations(Collection $reservations): self
    {
        // Vider la collection actuelle de réservations
        $this->reservations = $reservations;

        // Associer la relation inverse à chaque réservation
        foreach ($reservations as $reservation) {
            $reservation->setUser($this); // Associe chaque réservation à cet utilisateur
        }

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function setComments(Collection $comments): self
    {
        // Vider la collection actuelle de commentaire
        $this->comments = $comments;

        // Associer la relation inverse à chaque commentaire
        foreach ($comments as $comment) {
            $comment->setUser($this); // Associe chaque commentaire à cet utilisateur
        }

        return $this;
    }
}