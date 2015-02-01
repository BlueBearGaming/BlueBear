<?php

namespace BlueBear\FileUploadBundle\EventListener;

use BlueBear\FileUploadBundle\Entity\Resource;
use BlueBear\FileUploadBundle\Manager\ResourceManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class ResourceSubscriber implements EventSubscriber
{

    /**
     * @var ResourceManager
     */
    protected $resourceManager;

    public function __construct(ResourceManager $resourceManager)
    {
        $this->resourceManager = $resourceManager;
    }

    public function getSubscribedEvents()
    {
        return [
            'preRemove',
        ];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Resource) {
            $this->resourceManager->removeResourceFile($entity);
            return;
        }
    }
}
