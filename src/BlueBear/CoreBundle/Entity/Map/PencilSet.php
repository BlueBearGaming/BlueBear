<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessorOrder;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use BlueBear\CoreBundle\Entity\Editor\Image;

/**
 * A pencil set is a collection of pencil. Each have a pencil set.
 *
 * @ORM\Table(name="pencil_set")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\PencilSetRepository")
 * @ExclusionPolicy("all")
 * @AccessorOrder("custom", custom={"id", "name", "label", "pencils"})
 */
class PencilSet
{
    use Id, Nameable, Label;

    const TYPE_SQUARE = 'square';
    const TYPE_ISOMETRIC = 'isometric';
    const TYPE_HEXAGON = 'hexagon';

    /**
     * List of pencils attached to the pencil set
     *
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Pencil", mappedBy="pencilSet")
     * @Expose()
     */
    protected $pencils;

    /**
     * @ORM\ManyToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Map")
     * @var ArrayCollection
     */
    protected $maps;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $type;
    
    /**
     * Image used in render
     *
     * @var Image
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Editor\Image", fetch="EAGER", cascade={"persist"});
     * @ORM\JoinColumn(nullable=true)
     */
    protected $sprite;

    /**
     * Return pencil set type references
     *
     * @return array
     */
    public static function getPencilSetType()
    {
        return [
            self::TYPE_SQUARE => 'Square',
            self::TYPE_HEXAGON => 'Hexagon',
            self::TYPE_ISOMETRIC => 'Isometric',
        ];
    }

    public function __construct()
    {
        $this->maps = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getPencils()
    {
        return $this->pencils;
    }

    /**
     * @param mixed $pencils
     */
    public function setPencils($pencils)
    {
        $this->pencils = $pencils;
    }

    public function getMaps()
    {
        return $this->maps;
    }

    public function addMap(Map $map)
    {
        $this->maps->add($map);
    }

    public function removeMap(Map $map)
    {
        $this->maps->remove($map->getId());
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Return pencil's set's sprite
     *
     * @return Image
     */
    public function getSprite()
    {
        return $this->sprite;
    }

    /**
     * Set item's sprite
     *
     * @param Image $sprite
     * @return $this
     */
    public function setSprite(Image $sprite)
    {
        $this->sprite = $sprite;
        $sprite->setPencil($this);
    }


    // TODO remove after api controller refactoring
    public function toArray()
    {
        $jsonPencils = [];
        foreach ($this->getPencils() as $pencil) {
            $jsonPencils[] = $pencil->toArray();
        }

        $jsonPencilSet = [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'pencils' => $jsonPencils,
        ];
        return $jsonPencilSet;
    }
} 