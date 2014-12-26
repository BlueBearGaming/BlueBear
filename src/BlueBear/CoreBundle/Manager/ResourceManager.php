<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\CoreBundle\Entity\Editor\Resource;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;
use Doctrine\ORM\EntityRepository;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ResourceManager
{
    use ManagerBehavior;

    /**
     * Upload a file into images directory. Cut it if it's a sprite
     *
     * @param UploadedFile $file
     * @param $uploadType
     * @param Image $image
     * @throws Exception
     */
    public function upload(UploadedFile $file, $uploadType, Image $image = null)
    {
        $imagesDirectory = $this->getResourcesDirectory();

        if ($uploadType == Image::IMAGE_TYPE_SINGLE_IMAGE) {
            // generate "unique" filename
            $fileName = $this->generateFileHash($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
            $file->move($imagesDirectory . '/images/', $fileName);

            // save resource into database
            $resource = new Resource();
            $resource->setLabel($file->getClientOriginalName());
            $resource->setName($file->getClientOriginalName());
            $resource->setFileName($fileName);
            $resource->setFilePath($this->getImageDirectory());
            // if no image was provided, we create a new one
            if (!$image) {
                $image = new Image();
            }
            $image->setName($file->getClientOriginalName());
            $image->setResource($resource);

            $this->save($resource, false);
            $this->save($image);
        } else if ($uploadType == Image::IMAGE_TYPE_RPG_MAKER_SPRITE) {
            // cut sprite into multiple images
            $images = $this->getImageManager()->splitSprite($file->getPathname(), $imagesDirectory . '/sprite/');

            /** @var Image $image */
            foreach ($images as $image) {
                // save resource into database
                $resource = new Resource();
                $resource->setName($image->getName());
                $resource->setFileName($this->generateFileHash($image->getName()) . '');
                $resource->setFilePath($imagesDirectory);
                $image->setResource($resource);
                $this->save($resource, false);
                $this->save($image, false);
            }
            $this->flush();
        } else {
            throw new Exception('Invalid upload type');
        }
    }

    /**
     * Return hash file name to upload
     *
     * @param $fileName
     * @return string
     */
    public function generateFileHash($fileName)
    {
        return uniqid(md5($fileName . '-' . time()));
    }

    public function getImageDirectory()
    {
        return '/resources/images/';
    }

    public function getSpriteDirectory()
    {
        return '/resources/sprite/';
    }

    public function getResourcesDirectory()
    {
        return __DIR__ . '/../../../../resources/';
    }

    public static function getApplicationDirectory()
    {
        return __DIR__ . '/../../../../';
    }

    /**
     * @return ImageManager
     */
    protected function getImageManager()
    {
        return $this->getContainer()->get('bluebear.manager.image');
    }

    /**
     * Retourne le repository courant
     *
     * @return EntityRepository
     */
    protected function getRepository()
    {
        // TODO: Implement getRepository() method.
    }
}