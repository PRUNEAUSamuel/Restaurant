<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

     /**
     * Trouve toutes les réservations d'un utilisateur
     *
     * @param \App\Entity\User $user
     * @return Reservation[]
     */

     public function findByUser($user)
     {
         return $this->createQueryBuilder('r')
             ->andWhere('r.user = :user')
             ->setParameter('user', $user)
             ->getQuery()
             ->getResult();
     }
}
