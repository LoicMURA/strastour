<?php

namespace App\Repository;

use App\Entity\UserCourses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserCourses|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCourses|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCourses[]    findAll()
 * @method UserCourses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCoursesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCourses::class);
    }

    // /**
    //  * @return UserCourses[] Returns an array of UserCourses objects
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
    public function findOneBySomeField($value): ?UserCourses
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
