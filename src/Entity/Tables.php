<?php

namespace App\Entity;

use App\Repository\TablesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TablesRepository::class)]
class Tables
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nb_places = null;

    #[ORM\Column]
    private ?int $table_number = null;

    /**
     * @var Collection<int, reservation>
     */
    #[ORM\ManyToMany(targetEntity: reservation::class, inversedBy: 'tables')]
    private Collection $relation;

    public function __construct()
    {
        $this->relation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nb_places;
    }

    public function setNbPlaces(int $nb_places): static
    {
        $this->nb_places = $nb_places;

        return $this;
    }

    public function getTableNumber(): ?int
    {
        return $this->table_number;
    }

    public function setTableNumber(int $table_number): static
    {
        $this->table_number = $table_number;

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getRelation(): Collection
    {
        return $this->relation;
    }

    public function addRelation(reservation $relation): static
    {
        if (!$this->relation->contains($relation)) {
            $this->relation->add($relation);
        }

        return $this;
    }

    public function removeRelation(reservation $relation): static
    {
        $this->relation->removeElement($relation);

        return $this;
    }
}
