<?php


namespace BlueBear\CoreBundle\Form\Editor;

use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\CoreBundle\Manager\ImageManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ImageToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ImageManager
     */
    protected $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * @param Image $image
     * @return mixed|void
     */
    public function transform($image)
    {
        $value = '';

        if ($image) {
            $value = $image->getId();
        }
        return $value;
    }

    public function reverseTransform($id = 0)
    {
        $image = null;

        if (!$id) {
            throw new TransformationFailedException('Invalid image id : ' . $id);
        } else {
            $image = $this->imageManager->find($id);
        }
        return $image;
    }
} 