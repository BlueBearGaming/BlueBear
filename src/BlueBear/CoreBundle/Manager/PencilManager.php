<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\PencilRepository;
use BlueBear\BaseBundle\Behavior\ManagerBehavior;

class PencilManager
{
    use ManagerBehavior;

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