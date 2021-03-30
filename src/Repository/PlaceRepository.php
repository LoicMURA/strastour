<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Place|null find($id, $lockMode = null, $lockVersion = null)
 * @method Place|null findOneBy(array $criteria, array $orderBy = null)
 * @method Place[]    findAll()
 * @method Place[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository
{
    private $coursePlaceRepository;

    public function __construct(ManagerRegistry $registry, CoursePlaceRepository $coursePlaceRepository)
    {
        parent::__construct($registry, Place::class);
        $this->coursePlaceRepository = $coursePlaceRepository;
    }

    // Return the id and name of all the places that aren't yet in a course
    public function findFreePlaces()
    {
        $coursePlaces = $this->coursePlaceRepository->findAll();
        $freePlaces = [];

        foreach ($coursePlaces as $cp) $freePlaces[] = $cp->getPlace();

        $manager = $this->getEntityManager();

        $queryBuilder = ($manager->createQueryBuilder())
            ->select('p.id', 'p.name')
            ->from('App\Entity\Place', 'p')
            ->where('p.id NOT IN (:places)')
            ->setParameter('places', $freePlaces);

        return $queryBuilder->getQuery()->getResult();
    }
}
