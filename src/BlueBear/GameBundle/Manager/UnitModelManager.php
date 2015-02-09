<?php

namespace BlueBear\GameBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerBehavior;

class UnitModelManager
{
    use ManagerBehavior;

    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBearGameBundle:UnitModel');
    }
}