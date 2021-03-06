<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function findAllForGame(): array {
        $manager = $this->getEntityManager();

        $queryBuilder = ($manager->createQueryBuilder())
            ->select('t.id', 't.name')
            ->from('App\Entity\Course', 't')
            ->orderBy('t.id');

        return $queryBuilder->getQuery()->getResult();
    }
}
