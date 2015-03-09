<?php

namespace BlueBear\FileUploadBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerTrait;
use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\FileUploadBundle\Entity\Resource;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use UnexpectedValueException;

class ResourceManager
{
    use ManagerTrait;

    /**
     * @todo REFACTOR
     */
    public function cleanUploads()
    {
//        $em = $this->getEntityManager();
//        $orphans = $em->getRepository('BlueBearCoreBundle:Editor\Image')->findOrphans();
//        foreach ($orphans as $orphan) {
//            $em->remove($orphan);
//        }
    }

    /**
     * Add an entry for Resource entity in database at each upload
     *
     * @param File $file
     * @param string $originalFilename
     * @param string $type
     * @return Resource
     */
    public function addFile(File $file, $originalFilename, $type = null)
    {
        // @todo dynamic instanciation
        if ($type === 'image') {
            $resource = new Image;
        }
        $resource->setOriginalFileName($originalFilename)
            ->setFileName($file->getFilename());

        $em = $this->getEntityManager();
        $em->persist($resource);
        $em->flush();

        return $resource;
    }

    /**
     * Remove a Resource from the hard drive
     * DOES NOT REMOVE THE ENTITY
     * @param Resource $resource
     */
    public function removeResourceFile(Resource $resource)
    {
        $fs = new Filesystem;
        try {
            $fs->remove($this->getUploadedFilePath($resource));
        } catch (UnexpectedValueException $e) {
            // @todo log error
        }
    }

    /**
     * Get the url of a "Resource" (for the web), only works if inside the web root directory
     * @param Resource $resource
     * @return string
     */
    public function getUploadedFileUrl(Resource $resource)
    {
        $webRoot = $this->container->getParameter('kernel.root_dir') . '/../web';
        $path = $this->getUploadedFilePath($resource);
        if (0 !== strpos($path, $webRoot)) {
            return $path;
        }
        return substr($path, strlen($webRoot));
    }

    /**
     * Get the path for an uploaded file, does not check if file exists
     * @throws UnexpectedValueException
     * @param Resource $resource
     * @return string
     */
    public function getUploadedFilePath(Resource $resource)
    {
        $directory = $this->getFileUploadBasePath($resource->getType());
        return $directory . '/' . $resource->getFileName();
    }

    /**
     * Get the base directory for a type of file upload configuration
     * @param string $type
     * @return string
     * @throws UnexpectedValueException
     */
    public function getFileUploadBasePath($type)
    {
        if (!$this->container->hasParameter('oneup_uploader.config.' . $type)) {
            throw new UnexpectedValueException("Unknown file upload configuration type '{$type}'");
        }
        $config = $this->container->getParameter('oneup_uploader.config.' . $type);
        $directory = $config['storage']['directory'];
        if (!$directory) {
            $directory = $this->container->getParameter('uploads.base_directory') . '/' . $type;
        }
        return rtrim($directory, '/');
    }

    /**
     * Return current manager repository
     *
     * @return EntityRepository
     */
    protected function getRepository()
    {
        // TODO: Implement getRepository() method.
    }
}