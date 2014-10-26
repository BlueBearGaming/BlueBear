<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\BackofficeBundle\Utils\Sprite\SpriteSplitter;
use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\CoreBundle\Entity\Editor\ImageRepository;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;
use Doctrine\Common\Collections\ArrayCollection;

class ImageManager
{
    use ManagerBehavior;

    /**
     * Split the sprite in $file into multiple images
     *
     * @param $spriteFullPath
     * @param $destinationDirectory
     * @throws \Exception
     * @return array
     */
    public function splitSprite($spriteFullPath, $destinationDirectory)
    {
        $images = [];
        // split the sprite
        $splitter = new SpriteSplitter();
        $imagesFilename = $splitter->split($spriteFullPath, $destinationDirectory);

        foreach ($imagesFilename as $imageName => $imagePath) {
            // we create an image and associate it to an editor item
            $image = new Image();
            $image->setName($imageName);
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