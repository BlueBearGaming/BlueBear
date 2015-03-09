<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\PencilRepository;
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
        return $this->getEntityManager()->getRepository('BlueBear\CoreBundle\Entity\Map\Pencil');
    }
}