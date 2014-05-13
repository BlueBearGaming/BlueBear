<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Sizable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\ORM\Mapping as ORM;

/**
 * The map.
 *
 * @ORM\Table(name="map")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\MapRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Map
{
    use Id, Nameable, Label, Sizable, Timestampable, Typeable;

    /**
     * Map pencil set
     *
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\PencilSet", mappedBy="map", fetch="EAGER")
     */
    protected $pencilSets;

    /**
     * @return mixed
     */
    public function getPencilSets()
    {
        return $this->pencilSets;
    }

    /**
     * @param mixed $pencilSets
     */
    public function setPencilSets($pencilSets)
    {
        $this->pencilSets = $pencilSets;
    }
}
