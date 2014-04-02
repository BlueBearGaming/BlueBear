<?php

namespace BlueBear\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * A Map Item is an object that is positionned on the map at a specific layer and that will use the pencil as its renderer
 *
 * @ORM\Table(name="map_item", uniqueConstraints={@UniqueConstraint(name="pos_idx", columns={"map_id", "layer_id", "x", "y"})})
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\MapItemRepository")
 */
class MapItem {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \BlueBear\CoreBundle\Entity\Map
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map", inversedBy="items", fetch="EAGER");
     */
    protected $map;

    /**
     * @var \BlueBear\CoreBundle\Entity\Pencil
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Pencil", fetch="EAGER");
     */
    protected $pencil;

    /**
     * @var \BlueBear\CoreBundle\Entity\Layer
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Layer", fetch="EAGER");
     */
    protected $layer;

    /**
     * Absolute X position of the object in the map
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $x;

    /**
     * Absolute Y position of the object in the map
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $y;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getMap() {
        return $this->map;
    }

    public function setMap(\BlueBear\CoreBundle\Entity\Map $map) {
        $this->map = $map;
        return $this;
    }

    public function getPencil() {
        return $this->pencil;
    }

    public function setPencil(\BlueBear\CoreBundle\Entity\Pencil $pencil) {
        $this->pencil = $pencil;
        return $this;
    }

    public function getLayer() {
        return $this->layer;
    }

    public function setLayer(\BlueBear\CoreBundle\Entity\Layer $layer) {
        $this->layer = $layer;
        return $this;
    }

    public function getX() {
        return $this->x;
    }

    public function setX($x) {
        $this->x = $x;
        return $this;
    }

    public function getY() {
        return $this->y;
    }

    public function setY($y) {
        $this->y = $y;
        return $this;
    }


}
