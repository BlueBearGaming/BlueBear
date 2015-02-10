<?php

namespace BlueBear\GameBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerBehavior;

class EntityModelManager
{
    use ManagerBehavior;

    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBearGameBundle:EntityModel');
    }
}