<?php

namespace BlueBear\EngineBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class EntityInstanceRepository extends EntityRepository
{
    /**
     * Return an entity instance by its context and its position
     *
     * @param $contextId
     * @param $x
     * @param $y
     * @param $instanceType
     * @return QueryBuilder
     */
    public function findByTypeAndPosition($contextId, $x, $y, $instanceType)
    {
        return $this
            ->createQueryBuilder('entity')
            ->join('entity.mapItem', 'mapItem')
            ->join('mapItem.context', 'context')
            ->where('context.id = :context_id')
            ->andWhere('mapItem.x = :x')
            ->andWhere('mapItem.y = :y')
            ->andWhere('entity.type = :instance_type')
            ->setParameters( [
                'context_id' => $contextId,
                'x' => $x,
                'y' => $y,
                'instance_type' => $instanceType
            ]);
    }
}