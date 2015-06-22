<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\BaseBundle\Entity\Behaviors\Id;
use BlueBear\BaseBundle\Entity\Behaviors\Nameable;
use Doctrine\ORM\Mapping as ORM;

class Player
{
    use Id, Nameable;

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
