<?php

namespace App\Repository\Map;

use App\Entity\Map\Map;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class MapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Map::class);
    }

    /**
     * Find a map by its id
     *
     * @param $id
     * @return QueryBuilder
     */
    public function findMap($id)
    {
        return $this
            ->createQueryBuilder('map')
            ->addSelect('pencilSets, layers')
            ->leftJoin('map.pencilSets', 'pencilSets')
            ->leftJoin('map.layers', 'layers')
            ->where('map.id = :id')
            ->setParameter('id', $id);
    }

    /**
     * Find the first map in repository
     *
     * @return QueryBuilder
     */
    public function findOne()
    {
        return $this
            ->createQueryBuilder('map')
            ->setMaxResults(1);
    }

    /**
     * Return number of entities in map repository
     *
     * @return mixed
     */
    public function count()
    {
        return $this
            ->createQueryBuilder('map')
            ->select('COUNT(map.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
