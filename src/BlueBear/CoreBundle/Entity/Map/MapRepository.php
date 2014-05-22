<?php

namespace BlueBear\CoreBundle\Entity\Map;

use Doctrine\ORM\EntityRepository;

class MapRepository extends EntityRepository
{
    public function findMap($id)
    {
        return $this
            ->createQueryBuilder('map')
            ->leftJoin('map.pencilSets', 'pencilSets')
            ->where('map.id = :id')
            ->setParameter('id', $id);
    }
}