<?php

namespace BlueBear\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represent a pencil, a model that is used to "paint" tiles and object on the map
 *
 * @ORM\Table(name="pencil")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\PencilRepository")
 */
class Pencil {

    /**
     * Unique name
     * @ORM\Id
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    protected $id;

    /**
     * Item type (unit, land...)
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $type = 'terrain';

    /**
     * @var \BlueBear\CoreBundle\Entity\Editor\Image
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Editor\Image", fetch="EAGER");
     */
    protected $image;

    /**
     * Relative width in cell units (1 means fill an entire cell)
     * @var float
     * @ORM\Column(type="float")
     */
    protected $width = 1;

    /**
     * Relative height in cell units (1 means fill an entire cell)
     * @var float
     * @ORM\Column(type="float")
     */
    protected $height = 1;

    /**
     * Relative position of the center of the image to the center of the cell
     * @var float
     * @ORM\Column(type="float")
     */
    protected $imageX = 0;

    /**
     * Relative position of the center of the image to the center of the cell
     * @var float
     * @ORM\Column(type="float")
     */
    protected $imageY = 0;

    /**
     * Cells that physically contains the object (the one able to capture events)
     * @var array
     * @ORM\Column(type="array")
     */
    protected $boundingBox = [[0, 0]];

    /**
     * Tags
     * @var string
     * @ORM\Column(type="text")
     */
    protected $tags = '';

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage(\BlueBear\CoreBundle\Entity\Editor\Image $image) {
        $this->image = $image;
        return $this;
    }

    public function getWidth() {
        return $this->width;
    }

    public function setWidth($width) {
        $this->width = $width;
        return $this;
    }

    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
        return $this;
    }

    public function getImageX() {
        return $this->imageX;
    }

    public function setImageX($imageX) {
        $this->imageX = $imageX;
        return $this;
    }

    public function getImageY() {
        return $this->imageY;
    }

    public function setImageY($imageY) {
        $this->imageY = $imageY;
        return $this;
    }

    public function getBoundingBox() {
        return $this->boundingBox;
    }

    public function setBoundingBox($boundingBox) {
        $this->boundingBox = $boundingBox;
        return $this;
    }

    public function getTags() {
        return $this->tags;
    }

    public function setTags($tags) {
        $this->tags = $tags;
        return $this;
    }

}
