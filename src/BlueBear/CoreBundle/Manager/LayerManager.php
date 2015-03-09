<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\LayerRepository;
use BlueBear\BaseBundle\Behavior\ManagerTrait;

class LayerManager
{
    use ManagerTrait;

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