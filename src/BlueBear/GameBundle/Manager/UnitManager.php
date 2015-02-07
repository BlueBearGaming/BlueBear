<?php

namespace BlueBear\GameBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\GameBundle\Entity\UnitInstance;
use BlueBear\GameBundle\Entity\UnitInstanceRepository;
use BlueBear\GameBundle\Entity\UnitRepository;

class UnitManager
{
    use ManagerBehavior;

    /**
     * Return a unit instance by its position and its context
     *
     * @param Context $context
     * @param Position $position
     * @return UnitInstance
     */
    public function findInstanceByPosition(Context $context, Position $position)
    {
        return $this
            ->getInstanceRepository()
            ->findByPosition($context->getId(), $position->getX(), $position->getY())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Return current manager repository
     *
     * @return UnitRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBearGameBundle:Unit');
    }

    /**
     * Return current manager repository
     *
     * @return UnitInstanceRepository
     */
    protected function getInstanceRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBearGameBundle:UnitInstance');
    }
}