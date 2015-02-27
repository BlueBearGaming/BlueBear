<?php

namespace BlueBear\CoreBundle\Entity\Map;

use Doctrine\ORM\EntityRepository;

class ContextRepository extends EntityRepository
{
    public function findWithLimit($contextId, $startingX, $startingY, $endingX, $endingY)
    {
        return $this
            ->createQueryBuilder('context')
            ->addSelect('mapItems')
            ->join('context.mapItems', 'mapItems')
            ->where('context.id = :context_id')
            ->andWhere('mapItems.x >= :starting_x')
            ->andWhere('mapItems.y >= :starting_y')
            ->andWhere('mapItems.x <= :ending_x')
            ->andWhere('mapItems.y <= :ending_y')
            ->setParameters([
                'context_id' => $contextId,
                'starting_x' => $startingX,
                'starting_y' => $startingY,
                'ending_x' => $endingX,
                'ending_y' => $endingY
            ]);
    }
}