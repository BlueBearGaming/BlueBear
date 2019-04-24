<?php

namespace BlueBear\FileUploadBundle\EventListener;

use Exception;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use BlueBear\FileUploadBundle\Manager\ResourceManager;

class ResourceUploader
{
    /**
     *
     * @var ResourceManager
     */
    protected $resourceManager;

    public function __construct($resourceManager)
    {
        $this->resourceManager = $resourceManager;
    }

    public function onUpload(PostPersistEvent $event)
    {
        $file     = $event->getFile();
        $response = $event->getResponse();

        try {
            $originalFiles = $event->getRequest()->files->all()['files'];
            $originalFilename = array_pop($originalFiles)->getClientOriginalName();
        } catch (\Exception $e) {
            $originalFilename = $file->getFilename();
        }

        $file = $this->resourceManager->addFile($file, $originalFilename, $event->getType());
        $this->resourceManager->cleanUploads();

        $response[] = $file;
    }
}
