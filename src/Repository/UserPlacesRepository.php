<?php

namespace App\Repository;

use App\Entity\UserPlaces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserPlaces|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPlaces|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPlaces[]    findAll()
 * @method UserPlaces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPlacesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPlaces::class);
    }

    // /**
    //  * @return UserPlaces[] Returns an array of UserPlaces objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserPlaces
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
