<?php

namespace BlueBear\GameBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class UnitInstanceRepository extends EntityRepository
{
    /**
     * Return an unit instance by its context and its position
     *
     * @param $contextId
     * @param $x
     * @param $y
     * @return QueryBuilder
     */
    public function findByPosition($contextId, $x, $y)
    {
        return $this
            ->createQueryBuilder('unit')
            ->join('unit.mapItem', 'mapItem')
            ->join('mapItem.context', 'context')
            ->where('context.id = :context_id')
            ->andWhere('mapItem.x = :x')
            ->andWhere('mapItem.y = :y')
            ->setParameter('context_id', $contextId)
            ->setParameter('x', $x)
            ->setParameter('y', $y);
    }
}