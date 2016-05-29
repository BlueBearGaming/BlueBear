<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use BlueBear\CoreBundle\Entity\Editor\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

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
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Pencil", fetch="EAGER", mappedBy="pencilSet")
     * @Serializer\Expose()
     */
    protected $pencils;

    /**
     * @ORM\ManyToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Map")
     * @var ArrayCollection
     */
    protected $maps;
    
//    /**
//     * Sprite used to group pencils images
//     *
//     * @var Image
//     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Editor\Image", inversedBy="pencilSet", cascade={"persist", "remove"})
//     * @ORM\JoinColumn(name="sprite_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
//     * @Serializer\Expose()
//     */
//    protected $sprite;

    public function __construct()
    {
        $this->maps = new ArrayCollection();
    }

    /**
     * @return Pencil[]|Collection
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

    /**
     * @return Map[]|Collection
     */
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

    /**
     * @param string $pencilName
     * @return Pencil|null
     */
    public function getPencilByName($pencilName)
    {
        foreach ($this->getPencils() as $pencil) {
            if ($pencil->getName() === $pencilName) {
                return $pencil;
            }
        }
        return null;
    }
}
