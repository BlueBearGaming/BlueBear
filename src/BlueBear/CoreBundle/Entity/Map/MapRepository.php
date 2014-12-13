<?php

namespace BlueBear\CoreBundle\Entity\Map;

use Doctrine\ORM\EntityRepository;

class MapRepository extends EntityRepository
{
    public function findMap($id)
    {
        return $this
            ->createQueryBuilder('map')
            ->addSelect('pencilSets')
            ->leftJoin('map.pencilSets', 'pencilSets')
            ->where('map.id = :id')
            ->setParameter('id', $id);
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