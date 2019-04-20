<?php

namespace BlueBear\FileUploadBundle\Twig;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\FileUploadBundle\Entity\Resource;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UploadExtension extends AbstractExtension
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
            ->getExtension(AssetExtension::class)
            ->getAssetUrl('resources/images/'.$fileName, null, $absolute);
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('resource_path', [$this, 'resource_path']),
        ];
    }

    public function getName()
    {
        return 'bluebear_upload_extension';
    }
}
