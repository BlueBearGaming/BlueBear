<?php

namespace BlueBear\GameBundle\Manager;

use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;
use BlueBear\GameBundle\Entity\UnitRepository;

class UnitManager
{
    use ManagerBehavior;

    /**
     * Return current manager repository
     *
     * @return UnitRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBearGameBundle:Unit');
    }
}