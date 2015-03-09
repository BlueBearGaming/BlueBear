<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerTrait;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\ContextRepository;
use BlueBear\CoreBundle\Utils\Position;

class ContextManager
{
    use ManagerTrait;

    /**
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
                $startingPoint->x,
                $startingPoint->y,
                $endingPoint->x,
                $endingPoint->y
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