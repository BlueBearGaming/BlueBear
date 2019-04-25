<?php

namespace App\Entity\Map;

use App\Entity\Behavior\Id;
use App\Entity\Behavior\Label;
use App\Entity\Behavior\Nameable;
use App\Entity\Behavior\Typeable;
use App\Entity\Editor\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * A pencil set is a collection of pencil. Each have a pencil set.
 *
 * @ORM\Table(name="pencil_set")
 * @ORM\Entity(repositoryClass="App\Entity\Map\PencilSetRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class PencilSet
{
    use Id, Nameable, Label, Typeable;

    /**
     * List of pencils attached to the pencil set
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Map\Pencil", fetch="EAGER", mappedBy="pencilSet")
     * @Serializer\Expose()
     */
    protected $pencils;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Map\Map")
     * @var ArrayCollection
     */
    protected $maps;
    
    /**
     * Sprite used to group pencils images
     *
     * @var Image
     * @ORM\OneToOne(targetEntity="App\Entity\Editor\Image", inversedBy="pencilSet", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="sprite_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Serializer\Expose()
     */
    protected $sprite;

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
