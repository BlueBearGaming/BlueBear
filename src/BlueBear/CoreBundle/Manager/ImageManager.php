<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Editor\ImageRepository;
use BlueBear\FileUploadBundle\Manager\ResourceManager;

class ImageManager extends ResourceManager
{
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