<?php

namespace App\Repository\Map;

use App\Entity\Map\Map;
use App\Entity\Map\PencilSet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class PencilSetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PencilSet::class);
    }

    public function findByMap(Map $map)
    {
        return $this
            ->createQueryBuilder('pencilSet')
            ->leftJoin('pencilSet.maps', 'maps')
            ->where('maps.id = :map_id')
            ->setParameter('map_id', $map->getId());
    }
}
