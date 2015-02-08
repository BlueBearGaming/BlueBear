<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\LayerRepository;
use BlueBear\BaseBundle\Behavior\ManagerBehavior;

class LayerManager
{
    use ManagerBehavior;

    /**
     * Return layers repository
     *
     * @return LayerRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBear\CoreBundle\Entity\Map\Layer');
    }
}