<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\PencilRepository;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;

class PencilManager
{
    use ManagerBehavior {
        save as parentSave;
    }

    public function save($pencil, $flush = true)
    {
        // removing previous image link before saving new one
        $this->getContainer()->get('bluebear.manager.image')->unlinkPencil($pencil);
        $this->parentSave($pencil, $flush);
    }

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