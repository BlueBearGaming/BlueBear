<?php

namespace BlueBear\GameBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerTrait;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\GameBundle\Entity\EntityInstance;
use BlueBear\GameBundle\Entity\EntityInstanceRepository;
use Doctrine\ORM\NonUniqueResultException;

class EntityInstanceManager
{
    use ManagerTrait;

    /**
     * Return a entity instance by its position and its context
     *
     * @param Context $context
     * @param string $instanceType
     * @param Position $position
     * @return EntityInstance
     * @throws NonUniqueResultException
     */
    public function findByTypeAndPosition(Context $context, $instanceType, Position $position)
    {
        return $this
            ->getRepository()
            ->findByTypeAndPosition($context->getId(), $position->x, $position->y, $instanceType)
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