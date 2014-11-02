<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Descriptionable;
use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Sizable;
use BlueBear\CoreBundle\Entity\Behavior\Taggable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use BlueBear\CoreBundle\Entity\Editor\Image;
use Doctrine\ORM\Mapping as ORM;

/**
 * A pencil is a model that is used to "paint" tiles and object on the map. Each map have a pencil
 * set which have a list of pencils
 *
 * @ORM\Table(name="pencil")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\PencilRepository")
 */
class Pencil
{
    use Id, Nameable, Label, Descriptionable, Sizable, Typeable, Taggable;

    /**
     * Image used in render
     *
     * @var Image
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Editor\Image", fetch="EAGER", cascade={"persist"});
     * @ORM\JoinColumn(nullable=true)
     */
    protected $image;

    /**
     * PencilSet which this pencil belongs
     *
     * @ORM\JoinColumn(name="pencil_set_id")
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\PencilSet", inversedBy="pencils")
     */
    protected $pencilSet;

    /**
     * Allowed layers for this pencil
     *
     * @ORM\ManyToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Layer")
     * @ORM\JoinTable(name="pencil_allowed_layers")
     */
    protected $allowedLayers;

    /**
     * Relative position of the center of the image to the center of the cell
     *
     * @ORM\Column(type="float")
     */
    protected $imageX = 0;

    /**
     * Relative position of the center of the image to the center of the cell
     *
     * @ORM\Column(type="float")
     */
    protected $imageY = 0;

    /**
     * Cells that physically contains the object (the one able to capture events)
     *
     * @ORM\Column(type="array")
     */
    protected $boundingBox = [[0, 0]];

    public function getImageX()
    {
        return $this->imageX;
    }

    public function setImageX($imageX)
    {
        $this->imageX = $imageX;
        return $this;
    }

    public function getImageY()
    {
        return $this->imageY;
    }

    public function setImageY($imageY)
    {
        $this->imageY = $imageY;
        return $this;
    }

    public function getBoundingBox()
    {
        return $this->boundingBox;
    }

    public function setBoundingBox($boundingBox)
    {
        $this->boundingBox = $boundingBox;
        return $this;
    }

    /**
     * @return PencilSet
     */
    public function getPencilSet()
    {
        return $this->pencilSet;
    }

    public function setPencilSet(PencilSet $pencilSet)
    {
        $this->pencilSet = $pencilSet;
    }

    public function getAllowedLayers()
    {
        return $this->allowedLayers;
    }

    public function setAllowedLayers($allowedLayers)
    {
        $this->allowedLayers = $allowedLayers;
    }

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
        $image->setPencil($this);
    }
}
