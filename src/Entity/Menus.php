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

    #[ORM\Column(length: 255)]
    private ?string $relation = null;

    /**
     * @var Collection<int, produits>
     */
    #[ORM\ManyToMany(targetEntity: produits::class, inversedBy: 'menuses')]
    private Collection $produit_id;

    public function __construct()
    {
        $this->produit_id = new ArrayCollection();
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

    public function getRelation(): ?string
    {
        return $this->relation;
    }

    public function setRelation(string $relation): static
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * @return Collection<int, produits>
     */
    public function getProduitId(): Collection
    {
        return $this->produit_id;
    }

    public function addProduitId(produits $produitId): static
    {
        if (!$this->produit_id->contains($produitId)) {
            $this->produit_id->add($produitId);
        }

        return $this;
    }

    public function removeProduitId(produits $produitId): static
    {
        $this->produit_id->removeElement($produitId);

        return $this;
    }
}
