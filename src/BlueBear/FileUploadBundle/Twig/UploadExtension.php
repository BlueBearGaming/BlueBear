<?php

namespace BlueBear\FileUploadBundle\Twig;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\FileUploadBundle\Entity\Resource;
use Twig_Extension;
use Twig_SimpleFunction;

class UploadExtension extends Twig_Extension
{
    use ContainerTrait;

    public function resource_path(Resource $resource = null, $absolute = false)
    {
        $fileName = '';
        if ($resource) {
            $fileName = $resource->getFileName();
        }
        return $this
            ->getContainer()
            ->get('twig')
            ->getExtension('asset')
            ->getAssetUrl('resources/images/' . $fileName, null, $absolute);
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('resource_path', [$this, 'resource_path'])
        ];
    }

    public function getName()
    {
        return 'bluebear_upload_extension';
    }
}
