<?php

namespace App\Repository;

use App\Entity\{UserPlaces, User};
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

    // Return the ids of all the places that are checked by the user
    public function findUser(User $user): array
    {
        $manager = $this->getEntityManager();

        $queryBuilder = ($manager->createQueryBuilder())
            ->select('p.id')
            ->from('App\Entity\UserPlaces', 't')
            ->join('t.place', 'p')
            ->andWhere('t.user = :user')
            ->setParameter('user', $user);

        $result = $queryBuilder->getQuery()->getResult();
        $places = [];

        foreach ($result as $place) {
            $places[] = $place['id'];
        }

        return $places;
    }
}
