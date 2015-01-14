<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\BackofficeBundle\Utils\Sprite\SplitRules;
use BlueBear\BackofficeBundle\Utils\Sprite\SpriteSplitter;
use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\CoreBundle\Entity\Editor\ImageRepository;
use BlueBear\CoreBundle\Entity\Editor\Resource;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use SplFileInfo;
use UnexpectedValueException;

class ImageManager
{
    use ManagerBehavior;

    /**
     * Split the sprite in $file into multiple images
     *
     * @param $spriteFullPath
     * @param $destinationDirectory
     * @throws Exception
     * @return array
     */
    public function splitSprite($spriteFullPath, $destinationDirectory, $splitType)
    {
        if ($splitType == Image::IMAGE_TYPE_RPG_MAKER_SPRITE) {
            $rules = new SplitRules();
            $rules->destinationX = 0;
            $rules->destinationY = 0;
            $rules->width = 32;
            $rules->height = 48;
        } elseif($splitType == Image::IMAGE_TYPE_RPG_MAKER_TILESET) {
            $rules = new SplitRules();
            $rules->width = 31;
            $rules->height = 31;
        } else {
            throw new UnexpectedValueException("Split type {$splitType} not supported");
        }
        $images = [];
        // split the sprite
        $splitter = new SpriteSplitter();
        $imagesFilename = $splitter->split($spriteFullPath, $destinationDirectory, $rules);

        foreach ($imagesFilename as $imageName => $imagePath) {
            // we create an image and associate it to an editor item
            $image = new Image();
            $image->setName($imageName);
            $resource = new Resource(new SplFileInfo($imagePath));
            $resource->setName('sprite_'.$imageName);
            $image->setResource($resource);
            $this->save($image, false);
            $images[] = $image;
        }
        $this->flush();

        return $images;
    }

    /**
     * Return orphans images (ie images with no item attached)
     *
     * @param Pencil $pencil
     * @return ArrayCollection
     */
    public function findOrphans(Pencil $pencil = null)
    {
        $pencilId = $pencil ? $pencil->getId() : 0;

        return $this
            ->getRepository()
            ->findOrphans($pencilId)
            ->setMaxResults('25')
            ->getQuery()
            ->getResult();
    }

    public function unlinkPencil(Pencil $pencil)
    {
        /**
         * @var Image $image
         */
        $image = $this->getRepository()->findOneBy([
            'pencil' => $pencil
        ]);

        if ($image) {
            $image->setPencil(null);
        }

    }

    /**
     *
     *
     * @return ImageRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBear\CoreBundle\Entity\Editor\Image');
    }
}