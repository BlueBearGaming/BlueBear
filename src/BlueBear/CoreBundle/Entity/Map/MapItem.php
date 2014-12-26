<?php

namespace BlueBear\CoreBundle\Entity\Map;

use Doctrine\ORM\Mapping as ORM;

/**
 * A Map Item is an object that is positioned on the map at a specific layer and that will use the pencil as its renderer
 *
 * @ORM\Table(name="map_item")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\MapItemRepository")
 */
class MapItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Pencil
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Pencil", inversedBy="mapItems")
     */
    protected $pencil;

    /**
     * Layer
     *
     * @var Layer
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Layer", fetch="EAGER");
     */
    protected $layer;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Context", inversedBy="mapItems")
     */
    protected $context;

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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getPencil()
    {
        return $this->pencil;
    }

    public function setPencil(Pencil $pencil)
    {
        $this->pencil = $pencil;
        return $this;
    }

    public function getLayer()
    {
        return $this->layer;
    }

    public function setLayer(Layer $layer)
    {
        $this->layer = $layer;
        return $this;
    }

    public function getX()
    {
        return $this->x;
    }

    public function setX($x)
    {
        $this->x = $x;
        return $this;
    }

    public function getY()
    {
        return $this->y;
    }

    public function setY($y)
    {
        $this->y = $y;
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'pencilName' => $this->getPencil()->getName(),
            'x' => $this->getX(),
            'y' => $this->getY(),
        ];
    }
}