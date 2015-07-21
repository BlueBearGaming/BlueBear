<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerTrait;
use BlueBear\CoreBundle\Entity\Map\Army;
use Doctrine\ORM\EntityRepository;

class ArmyManager
{
    use ManagerTrait;

    public function reset(Army $army)
    {
        $army->setEntityInstances(null);
        $this->save($army);
    }

    /**
     * Return current manager repository
     *
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this
            ->getEntityManager()
            ->getRepository('BlueBearCoreBundle:Map\Army');
    }
}
