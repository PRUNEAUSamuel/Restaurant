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

 
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'tables')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
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

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservations(Reservation $reservations): static
    {
        if (!$this->reservations->contains($reservations)) {
            $this->reservations->add($reservations);
        }

        return $this;
    }

    public function removeRelation(Reservation $reservations): static
    {
        $this->reservations->removeElement($reservations);

        return $this;
    }
}
