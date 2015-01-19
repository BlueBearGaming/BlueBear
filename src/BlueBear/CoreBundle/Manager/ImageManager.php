<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Editor\ImageRepository;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;

class ImageManager
{
    use ManagerBehavior;

    /**
     *
     *
     * @return ImageRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBear\CoreBundle\Entity\Editor\Image');
    }
}