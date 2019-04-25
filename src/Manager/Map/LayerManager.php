<?php

namespace App\Manager\Map;

use App\Entity\Map\LayerRepository;
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
        return $this->getEntityManager()->getRepository('App\Entity\Map\Layer');
    }
}
