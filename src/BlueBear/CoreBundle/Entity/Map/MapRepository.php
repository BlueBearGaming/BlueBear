<?php

namespace BlueBear\CoreBundle\Entity\Map;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

class MapRepository extends EntityRepository
{
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
}
