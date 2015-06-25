<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerTrait;

class GameActionManager
{
    use ManagerTrait;

    public function getRepository()
    {
        return $this
            ->getEntityManager()
            ->getRepository('BlueBearCoreBundle:Game\GameAction');
    }
}
