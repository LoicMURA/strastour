<?php

namespace App\Repository;

use App\Entity\ItemBonuses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemBonuses|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemBonuses|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemBonuses[]    findAll()
 * @method ItemBonuses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemBonusesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemBonuses::class);
    }
}
