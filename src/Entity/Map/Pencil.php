<?php

namespace App\Entity\Map;

use App\Entity\Behavior\Descriptionable;
use App\Entity\Behavior\Id;
use App\Entity\Behavior\Label;
use App\Entity\Behavior\Nameable;
use App\Entity\Behavior\Sizable;
use App\Entity\Behavior\Taggable;
use App\Entity\Behavior\Typeable;
use App\Entity\Editor\Image;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * A pencil is a model that is used to "paint" tiles and object on the map. Each map have a pencil
 * set which have a list of pencils
 *
 * @ORM\Table(name="pencil")
 * @ORM\Entity(repositoryClass="App\Entity\Map\PencilRepository")
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
     * @ORM\OneToOne(targetEntity="App\Entity\Editor\Image", inversedBy="pencil", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Serializer\Expose()
     */
    protected $image;

    /**
     * PencilSet which this pencil belongs
     *
     * @ORM\JoinColumn(name="pencil_set_id")
     * @ORM\ManyToOne(targetEntity="App\Entity\Map\PencilSet", fetch="EAGER", inversedBy="pencils")
     */
    protected $pencilSet;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Map\MapItem", mappedBy="pencil")
     */
    protected $mapItems;

    /**
     * Allowed layers for this pencil
     *
     * @ORM\Column(name="allowed_layer_types", type="simple_array", nullable=true)
     * @Serializer\Expose()
     */
    protected $allowedLayerTypes;

    /**
     * Relative position of the center of the image to the center of the cell
     *
     * @ORM\Column(name="image_x", type="float", nullable=true)
     * @Serializer\Expose()
     */
    protected $imageX = 0;

    /**
     * Relative position of the center of the image to the center of the cell
     *
     * @ORM\Column(name="image_y", type="float", nullable=true)
     * @Serializer\Expose()
     */
    protected $imageY = 0;

    /**
     * Position of the image inside the sprite sheet in pixels
     *
     * @ORM\Column(name="sprite_x", type="integer", nullable=true)
     * @Serializer\Expose()
     */
    protected $spriteX;

    /**
     * Position of the image inside the sprite sheet in pixels
     *
     * @ORM\Column(name="sprite_y", type="integer", nullable=true)
     * @Serializer\Expose()
     */
    protected $spriteY;

    /**
     * Size of the image in pixels
     *
     * @ORM\Column(name="sprite_width", type="integer", nullable=true)
     * @Serializer\Expose()
     */
    protected $spriteWidth;

    /**
     * Size of the image in pixels
     *
     * @ORM\Column(name="sprite_height", type="integer", nullable=true)
     * @Serializer\Expose()
     */
    protected $spriteHeight;

    /**
     * Cells that physically contains the object (the one able to capture events)
     *
     * @ORM\Column(name="bounding_box", type="array", nullable=true)
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
     * @return array
     */
    public function getAllowedLayerTypes()
    {
        return $this->allowedLayerTypes;
    }

    public function setAllowedLayerTypes(array $allowedLayerTypes = null)
    {
        $this->allowedLayerTypes = $allowedLayerTypes;
    }

    public function isLayerTypeAllowed($layerType)
    {
        return in_array($layerType, $this->allowedLayerTypes);
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
    public function setImage(Image $image = null)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMapItems()
    {
        return $this->mapItems;
    }

    /**
     * @param mixed $mapItems
     */
    public function setMapItems($mapItems)
    {
        $this->mapItems = $mapItems;
    }

    /**
     * @return mixed
     */
    public function getSpriteX()
    {
        return $this->spriteX;
    }

    /**
     * @param mixed $spriteX
     */
    public function setSpriteX($spriteX)
    {
        $this->spriteX = $spriteX;
    }

    /**
     * @return mixed
     */
    public function getSpriteY()
    {
        return $this->spriteY;
    }

    /**
     * @param mixed $spriteY
     */
    public function setSpriteY($spriteY)
    {
        $this->spriteY = $spriteY;
    }

    /**
     * @return mixed
     */
    public function getSpriteWidth()
    {
        return $this->spriteWidth;
    }

    /**
     * @param mixed $spriteWidth
     */
    public function setSpriteWidth($spriteWidth)
    {
        $this->spriteWidth = $spriteWidth;
    }

    /**
     * @return mixed
     */
    public function getSpriteHeight()
    {
        return $this->spriteHeight;
    }

    /**
     * @param mixed $spriteHeight
     */
    public function setSpriteHeight($spriteHeight)
    {
        $this->spriteHeight = $spriteHeight;
    }


}
