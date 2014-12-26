<?php


namespace BlueBear\CoreBundle\Entity\Map;

use Doctrine\ORM\EntityRepository;

class PencilSetRepository extends EntityRepository
{
    public function findByMap(Map $map)
    {
        return $this
            ->createQueryBuilder('pencilSet')
            ->leftJoin('pencilSet.maps', 'maps')
            ->where('maps.id = :map_id')
            ->setParameter('map_id', $map->getId());
    }
}