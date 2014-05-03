<?php


namespace BlueBear\CoreBundle\Entity\Behavior;

use BlueBear\CoreBundle\Entity\Editor\Image;

trait Imageable
{
    /**
     * Image used in render
     *
     * @var Image
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Editor\Image", fetch="EAGER");
     */
    protected $image;

    /**
     * Return item's image
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set item's image
     *
     * @param Image $image
     * @return $this
     */
    public function setImage(Image $image)
    {
        $this->image = $image;
        return $this;
    }
} 