<?php

namespace App\Manager\Map;

use App\Entity\Map\Context;
use App\Entity\Map\ContextRepository;
use App\Utils\Position;

class ContextManager
{
    public function __construct()
    {
    }

    /**
     * Return a context with its map item from a starting position to a ending position
     *
     * @param $contextId
     * @param Position $startingPoint
     * @param Position $endingPoint
     * @return Context
     */
    public function findWithLimit($contextId, Position $startingPoint, Position $endingPoint)
    {
        return $this
            ->getRepository()
            ->findWithLimit(
                $contextId,
                (int)$startingPoint->x,
                (int)$startingPoint->y,
                (int)$endingPoint->x,
                (int)$endingPoint->y
            )
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Return current manager repository
     *
     * @return ContextRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBearCoreBundle:Map\Context');
    }
}
