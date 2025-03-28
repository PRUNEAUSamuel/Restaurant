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
    private ?int $nbPlaces = null;

    #[ORM\Column]
    private ?int $tableNumber = null;

 
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'table')]
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
        return $this->nbPlaces;
    }

    public function setNbPlaces(int $nbPlaces): static
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    public function getTableNumber(): ?int
    {
        return $this->tableNumber;
    }

    public function setTableNumber(int $tableNumber): static
    {
        $this->tableNumber = $tableNumber;

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
