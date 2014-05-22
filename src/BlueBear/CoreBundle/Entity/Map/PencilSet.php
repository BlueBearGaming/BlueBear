<?php


namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use Doctrine\ORM\Mapping as ORM;

/**
 * A pencil set is a collection of pencil. Each have a pencil set.
 *
 * @ORM\Table(name="pencil_set")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\PencilSetRepository")
 */
class PencilSet
{
    use Id, Nameable, Label;

    /**
     * List of pencils attached to the pencil set
     *
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Pencil", mappedBy="pencilSet")
     */
    protected $pencils;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Map", inversedBy="pencilSets", cascade={"persist", "merge"})
     */
    protected $map;

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

    /**
     * @return mixed
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param Map $map
     */
    public function setMap($map)
    {
        $this->map = $map;
        die('set map');
    }
} 