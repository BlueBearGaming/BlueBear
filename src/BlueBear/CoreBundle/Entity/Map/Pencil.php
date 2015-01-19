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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * A pencil is a model that is used to "paint" tiles and object on the map. Each map have a pencil
 * set which have a list of pencils
 *
 * @ORM\Table(name="pencil")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\PencilRepository")
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\AccessorOrder("custom", custom={"id", "name", "label", "description", "type", "imageX", "imageY", "width", "height", "boundingBox", "allowedLayers", "image"})
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
     * @Serializer\Expose()
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
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\MapItem", mappedBy="pencil")
     */
    protected $mapItems;

    /**
     * Allowed layers for this pencil
     *
     * @ORM\Column(name="allowed_layer_types", type="simple_array")
     * @Serializer\Expose()
     */
    protected $allowedLayerTypes;

    /**
     * Relative position of the center of the image to the center of the cell
     *
     * @ORM\Column(name="image_x", type="float")
     * @Serializer\Expose()
     */
    protected $imageX = 0;

    /**
     * Relative position of the center of the image to the center of the cell
     *
     * @ORM\Column(name="image_y", type="float")
     * @Serializer\Expose()
     */
    protected $imageY = 0;

    /**
     * Position of the image inside the sprite sheet in pixels
     *
     * @ORM\Column(name="sprite_x", type="integer")
     * @Serializer\Expose()
     */
    protected $spriteX;

    /**
     * Position of the image inside the sprite sheet in pixels
     *
     * @ORM\Column(name="sprite_y", type="integer")
     * @Serializer\Expose()
     */
    protected $spriteY;

    /**
     * Size of the image in pixels
     *
     * @ORM\Column(name="sprite_width", type="integer")
     * @Serializer\Expose()
     */
    protected $spriteWidth;

    /**
     * Size of the image in pixels
     *
     * @ORM\Column(name="sprite_height", type="integer")
     * @Serializer\Expose()
     */
    protected $spriteHeight;

    /**
     * Cells that physically contains the object (the one able to capture events)
     *
     * @ORM\Column(type="array")
     * @Serializer\Expose()
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

    /**
     *
     * @return ArrayCollection
     */
    public function getAllowedLayerTypes()
    {
        return $this->allowedLayerTypes;
    }

    public function setAllowedLayerTypes(array $allowedLayerTypes = null)
    {
        $this->allowedLayerTypes = $allowedLayerTypes;
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
        return $this;
    }
}
