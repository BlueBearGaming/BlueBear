<?php

namespace BlueBear\GameBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerBehavior;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\GameBundle\Entity\UnitInstance;
use BlueBear\GameBundle\Entity\UnitInstanceRepository;

class UnitInstanceManager
{
    use ManagerBehavior;

    /**
     * Return a unit instance by its position and its context
     *
     * @param Context $context
     * @param Position $position
     * @return UnitInstance
     */
    public function findByPosition(Context $context, Position $position)
    {
        return $this
            ->getRepository()
            ->findByPosition($context->getId(), $position->getX(), $position->getY())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Return current manager repository
     *
     * @return UnitInstanceRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBearGameBundle:UnitInstance');
    }
}