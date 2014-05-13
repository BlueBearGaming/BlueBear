<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Editor\ImageRepository;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;
use BlueBear\BackofficeBundle\Utils\Sprite\SpriteSplitter;
use BlueBear\CoreBundle\Entity\Editor\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageManager
{
    use ManagerBehavior;

    /**
     * Split the sprite in $file into multiple images
     *
     * @param UploadedFile $file
     */
    public function splitSprite(UploadedFile $file)
    {
        // copy sprite file into tmp directory to split it
        $directory = realpath(__DIR__ . '/../../../../web/uploads/tmp') . '/';
        $file->move($directory, $file->getClientOriginalName());

        // split the sprite
        $splitter = new SpriteSplitter();
        $destinationDirectory = realpath(__DIR__ . '/../../../../web/uploads/sprites') . '/';
        $images = $splitter->split($directory . $file->getClientOriginalName(), $destinationDirectory);

        foreach ($images as $imageName => $imagePath) {
            // we create an image and associate it to an editor item
            $image = new Image();
            $image->setFilePath($imagePath);
            $image->setFileName($imageName);
            $image->setName($imageName);
            $this->save($image, false);
        }
        $this->flush();
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