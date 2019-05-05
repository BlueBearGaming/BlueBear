<?php

namespace App\Entity\Map;

use App\Entity\Behavior\Id;
use App\Entity\Behavior\Label;
use App\Entity\Behavior\Nameable;
use App\Entity\Behavior\Typeable;
use App\Entity\Editor\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * A pencil set is a collection of pencil. Each have a pencil set.
 */
class PencilSet
{
    use Id, Nameable, Label, Typeable;

    /**
     * List of pencils attached to the pencil set
     */
    protected $pencils;

    /**
     * @var ArrayCollection
     */
    protected $maps;
    
    /**
     * Sprite used to group pencils images
     *
     * @var Image
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
