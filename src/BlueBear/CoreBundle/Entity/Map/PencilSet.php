<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use BlueBear\CoreBundle\Entity\Editor\Image;

/**
 * A pencil set is a collection of pencil. Each have a pencil set.
 *
 * @ORM\Table(name="pencil_set")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\PencilSetRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class PencilSet
{
    use Id, Nameable, Label, Typeable;

    /**
     * List of pencils attached to the pencil set
     *
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Pencil", mappedBy="pencilSet")
     * @Serializer\Expose()
     */
    protected $pencils;

    /**
     * @ORM\ManyToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Map")
     * @var ArrayCollection
     */
    protected $maps;
    
    /**
     * Image used in render
     *
     * @var Image
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Editor\Image", fetch="EAGER", cascade={"persist"});
     * @ORM\JoinColumn(nullable=true)
     * @Serializer\Expose()
     */
    protected $sprite;

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
        return $this;
    }
}