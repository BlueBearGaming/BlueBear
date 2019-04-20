<?php

namespace BlueBear\HexagonBundle\Entity;

use BlueBear\EngineBundle\Entity\EntityInstance;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Unit extends EntityInstance
{
    /**
     * @return int
     */
    public function getX()
    {
        return $this->getMapItem()->getX();
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->getMapItem()->getY();
    }
}
