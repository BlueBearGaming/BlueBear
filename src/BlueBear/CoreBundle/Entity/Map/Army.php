<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="army")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\ArmyRepository")
 */
class Army
{
    use Id, Label, Nameable;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Player", inversedBy="armies")
     */
    protected $player;

    /**
     * @ORM\OneToMany(targetEntity="BlueBear\EngineBundle\Entity\EntityInstance", mappedBy="army")
     */
    protected $entityInstances;

    /**
     * @return mixed
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param mixed $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    /**
     * @param mixed $entityInstances
     */
    public function setEntityInstances($entityInstances)
    {
        $this->entityInstances = $entityInstances;
    }

    /**
     * @return mixed
     */
    public function getEntityInstances()
    {
        return $this->entityInstances;
    }
}
