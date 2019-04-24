<?php

namespace BlueBear\GameChessBundle\Entity;


use BlueBear\EngineBundle\Entity\EntityInstance;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Piece extends EntityInstance
{
    public function isWhite()
    {
        return substr($this->getName(), -5) === 'white';
    }

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