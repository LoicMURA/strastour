<?php

namespace App\Repository;

use App\Entity\CoursePlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CoursePlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoursePlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoursePlace[]    findAll()
 * @method CoursePlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursePlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoursePlace::class);
    }

    // /**
    //  * @return CoursePlace[] Returns an array of CoursePlace objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CoursePlace
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
