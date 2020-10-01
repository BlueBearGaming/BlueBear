<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\LayerRepository;
use BlueBear\BaseBundle\Behavior\ManagerTrait;
use BlueBear\CoreBundle\Entity\Map\Layer;

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
        return $this->getEntityManager()->getRepository(Layer::class);
    }
}
