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

/**
 * A pencil is a model that is used to "paint" tiles and object on the map. Each map have a pencil
 * set which have a list of pencils.
 */
class Pencil
{
    use Id, Nameable, Label, Descriptionable, Sizable, Typeable, Taggable;

    /**
     * Image used in render
     *
     * @var Image
     */
    protected $image;

    /**
     * PencilSet which this pencil belongs
     */
    protected $pencilSet;

    protected $mapItems;

    /**
     * Allowed layers for this pencil
     */
    protected $allowedLayerTypes;

    /**
     * Relative position of the center of the image to the center of the cell
     */
    protected $imageX = 0;

    /**
     * Relative position of the center of the image to the center of the cell
     */
    protected $imageY = 0;

    /**
     * Position of the image inside the sprite sheet in pixels
     */
    protected $spriteX;

    /**
     * Position of the image inside the sprite sheet in pixels
     */
    protected $spriteY;

    /**
     * Size of the image in pixels
     */
    protected $spriteWidth;

    /**
     * Size of the image in pixels
     */
    protected $spriteHeight;

    /**
     * Cells that physically contains the object (the one able to capture events)
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
