<?php

namespace App\Model\Map;

use App\Contracts\Map\Movable;
use App\Contracts\Map\Positionable;
use App\Contracts\Model\ModelInterface;

class Movement implements ModelInterface
{
    /**
     * @var Positionable
     */
    private $source;

    /**
     * @var Positionable
     */
    private $destination;

    /**
     * @var Movable
     */
    private $movable;

    public function __construct(Positionable $source, Positionable $destination, Movable $movable)
    {
        $this->source = $source;
        $this->destination = $destination;
        $this->movable = $movable;
    }

    public function getSource(): Positionable
    {
        return $this->source;
    }

    public function getDestination(): Positionable
    {
        return $this->destination;
    }

    public function getMovable(): Movable
    {
        return $this->movable;
    }
}
