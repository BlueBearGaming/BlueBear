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
     * @throws Exception
     */
    public function upload(UploadedFile $file, $uploadType)
    {
        $imagesDirectory = $this->getImageDirectory();

        if ($uploadType == Image::IMAGE_TYPE_SINGLE_IMAGE) {
            // generate "unique" filename
            $fileName = $this->generateFileHash($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
            $file->move($imagesDirectory, $fileName);

            // save resource into database
            $resource = new Resource();
            $resource->setLabel($file->getClientOriginalName());
            $resource->setName($file->getClientOriginalName());
            $resource->setFileName($fileName);
            $resource->setFilePath($imagesDirectory);
            $image = new Image();
            $image->setName($file->getClientOriginalName());
            $image->setResource($resource);

            $this->save($resource, false);
            $this->save($image);
        } else if ($uploadType == Image::IMAGE_TYPE_RPG_MAKER_SPRITE) {
            // cut sprite into multiple images
            $images = $this->getImageManager()->splitSprite($file->getPathname(), $imagesDirectory);

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
        $imageDirectory = __DIR__ . '/../../../../resources/images';

        if (!file_exists($imageDirectory)) {
            throw new Exception('Image path ' . $imageDirectory . ' does not exist !');
        }
        return realpath($imageDirectory);
    }

    public function getSpriteDirectory()
    {
        $imageDirectory = __DIR__ . '/../../../../resources/sprites/';

        if (!file_exists($imageDirectory)) {
            throw new Exception('Image path ' . $imageDirectory . ' does not exist !');
        }
        return realpath($imageDirectory);
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