<?php

namespace App\Manager\Map;

use App\Entity\Map\PencilRepository;
use BlueBear\BaseBundle\Behavior\ManagerTrait;

class PencilManager
{
    use ManagerTrait;

    /**
     * Return pencils repository
     *
     * @return PencilRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('App\Entity\Map\Pencil');
    }
}
