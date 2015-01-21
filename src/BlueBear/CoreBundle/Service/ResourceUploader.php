<?php

namespace BlueBear\CoreBundle\Service;

use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\CoreBundle\Entity\Editor\Resource;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Exception;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\User\UserInterface;

class ResourceUploader
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     *
     * @param  string     $filePath
     * @param  bool       $copy
     * @return Resource
     * @throws \Exception
     */
    public function handleFile($filePath, $copy = false)
    {
        if ($filePath instanceof File) {
            $filePath = $filePath->getRealPath();
        }

        if (!file_exists($filePath)) {
            throw new \Exception("File not found {$filePath}");
        }

        $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $filename = uniqid().'.'.$fileType;

        // @todo dynamically instanciate object with a parameter
        // This won't work with other resources
        $resource = new Image();
        $resource->setFileName($filename);
        $resource->setName(basename($filePath));
        $resource->setOriginalFileName(basename($filePath));

        $folder = $this->container->getParameter('resources_dir');
        if (!file_exists($folder)) {
            if (!mkdir($folder, 0777, true)) {
                throw new \Exception('Unable to create resource directory');
            }
        }
        $newfilePath = $folder.'/'.$filename;
        if ($copy) {
            $success = copy($filePath, $newfilePath);
            if (!$success) {
                throw new Exception("Unable to copy resource: {$filePath} => {$newfilePath}");
            }
        } else {
            $success = rename($filePath, $newfilePath);
            if (!$success) {
                throw new Exception("Unable to move resource: {$filePath} => {$newfilePath}");
            }
        }

        return $resource;
    }

    public function onUpload(PostPersistEvent $event)
    {
        $resource = $this->handleFile($event->getFile());
        try {
            $originalFiles = $event->getRequest()->files->all()['files'];
            $resource->setOriginalFilename(array_pop($originalFiles)->getClientOriginalName());
        } catch (\Exception $e) {
            // Woops ?
        }
        // @todo Trigger event for more extensibility ?
        $em = $this->getDoctrine()->getManager();
        $em->persist($resource);
        $em->flush();

        $response = $event->getResponse();
        $response['files'] = [
            [
                'name' => $resource->getOriginalFilename(),
                'href' => $this->getResourceUrl($resource),
            ]
        ];
    }

    /**
     * @return Registry
     */
    protected function getDoctrine()
    {
        return $this->container->get('doctrine');
    }

    /**
     * @return UserInterface
     */
    protected function getUser()
    {
        $token = $this->container->get('security.context')->getToken();
        if ($token) {
            return $token->getUser();
        }
    }

    /**
     * @return Router
     */
    protected function getResourceUrl(Resource $resource)
    {
        $router = $this->container->get('router');
        return $router->generate('bluebear_resource_proxy', ['filename' => $resource->getFilename()]);
    }
}
