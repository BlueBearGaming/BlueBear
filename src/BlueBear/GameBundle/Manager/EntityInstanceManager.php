<?php

namespace BlueBear\GameBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerBehavior;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\GameBundle\Entity\EntityInstance;
use BlueBear\GameBundle\Entity\EntityInstanceRepository;

class EntityInstanceManager
{
    use ManagerBehavior;

    /**
     * Return a entity instance by its position and its context
     *
     * @param Context $context
     * @param Position $position
     * @return EntityInstance
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
     * @return EntityInstanceRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBearGameBundle:EntityInstance');
    }
}