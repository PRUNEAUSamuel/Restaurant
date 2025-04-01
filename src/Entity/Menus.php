<?php

namespace App\Entity;

use App\Repository\MenusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenusRepository::class)]
class Menus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, produits>
     */
    #[ORM\ManyToMany(targetEntity: Produits::class, inversedBy: 'menus')]
    #[ORM\JoinTable(name: "produit_menu")]
    public Collection $produits;


    #[ORM\Column(type: "boolean")]
    public ?bool $actualMenu = false;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduits(Produits $produits): static
    {
        if (!$this->produits->contains($produits)) {
            $this->produits->add($produits);
        }

        return $this;
    }

    public function removeProduits(Produits $produits): static
    {
        $this->produits->removeElement($produits);

        return $this;
    }
}
