<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\BaseBundle\Entity\Behaviors\Id;
use BlueBear\BaseBundle\Entity\Behaviors\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="player")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\PlayerRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Player
{
    use Id, Nameable, Timestampable;

    /**
     * @ORM\Column(name="is_human", type="boolean")
     */
    protected $isHuman = false;

    /**
     * @return boolean
     */
    public function isIsHuman()
    {
        return $this->isHuman;
    }

    /**
     * @param boolean $isHuman
     */
    public function setIsHuman($isHuman)
    {
        $this->isHuman = $isHuman;
    }
}
