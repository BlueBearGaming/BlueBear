<?php

namespace App\Model\Map;

use App\Contracts\Model\ModelInterface;
use App\Entity\Engine\GameObject;

class Movement implements ModelInterface
{
    /**
     * @var GameObject
     */
    private $source;
    /**
     * @var GameObject
     */
    private $destination;
    /**
     * @var GameObject
     */
    private $movable;

    public function __construct(GameObject $source, GameObject $destination, GameObject $movable)
    {
        $this->source = $source;
        $this->destination = $destination;
        $this->movable = $movable;
    }

    /**
     * @return GameObject
     */
    public function getSource(): GameObject
    {
        return $this->source;
    }

    /**
     * @return GameObject
     */
    public function getDestination(): GameObject
    {
        return $this->destination;
    }

    /**
     * @return GameObject
     */
    public function getMovable(): GameObject
    {
        return $this->movable;
    }
}
