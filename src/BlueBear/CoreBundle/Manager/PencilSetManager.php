<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\PencilSetRepository;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;

class PencilSetManager
{
    use ManagerBehavior;

    /**
     * Return pencils repository
     *
     * @return PencilSetRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBear\CoreBundle\Entity\Map\PencilSet');
    }
}