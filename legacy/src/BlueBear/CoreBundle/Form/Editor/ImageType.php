<?php

namespace App\Form\Editor;

use App\Entity\Editor\ImageRepository;
use BlueBear\FileUploadBundle\Form\Type\ResourceType;

class ImageType extends ResourceType
{
    protected $className = 'App\Entity\Editor\Image';
    protected $endpoint = 'image';

    public function getName()
    {
        return 'resource_image';
    }

    protected function getQueryBuilder()
    {
        return function(ImageRepository $repo) {
            return $repo->getQbForOrphans();
        };
    }
}
