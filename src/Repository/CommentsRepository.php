<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comments>
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    /**
     * Trouve toutes les réservations d'un utilisateur
     *
     * @param \App\Entity\User $user
     * @return Comments[]
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
