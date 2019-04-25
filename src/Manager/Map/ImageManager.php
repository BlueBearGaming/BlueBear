<?php

namespace App\Manager\Map;

use App\Entity\Editor\ImageRepository;
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
        return $this->getEntityManager()->getRepository('App\Entity\Editor\Image');
    }
}
