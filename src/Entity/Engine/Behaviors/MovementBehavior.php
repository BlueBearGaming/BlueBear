<?php

namespace App\Entity\Engine\Behaviors;

use App\Entity\Engine\AbstractBehavior;

class MovementBehavior extends AbstractBehavior
{
    protected $x = 0;
    protected $y = 0;
    protected $z = 0;
    protected $movement = 0;
    protected $movementMax = 0;

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX(int $x): void
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY(int $y): void
    {
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getZ(): int
    {
        return $this->z;
    }

    /**
     * @param int $z
     */
    public function setZ(int $z): void
    {
        $this->z = $z;
    }

    /**
     * @return int
     */
    public function getMovement(): int
    {
        return $this->movement;
    }

    /**
     * @param int $movement
     */
    public function setMovement(int $movement): void
    {
        $this->movement = $movement;
    }

    /**
     * @return int
     */
    public function getMovementMax(): int
    {
        return $this->movementMax;
    }

    /**
     * @param int $movementMax
     */
    public function setMovementMax(int $movementMax): void
    {
        $this->movementMax = $movementMax;
    }
}
