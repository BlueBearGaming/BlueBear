<?php


namespace BlueBear\CoreBundle\Entity\Behavior;

use BlueBear\CoreBundle\Utils\Position;
use JMS\Serializer\Annotation as Serializer;

/**
 * Positionable
 *
 * Capacity to have a position (x and y coordinates)
 */
trait Positionable
{
    /**
     * X entity position
     *
     * @ORM\Column(name="x", type="integer")
     */
    protected $x;

    /**
     * Y entity position
     *
     * @ORM\Column(name="y", type="integer")
     */
    protected $y;

    /**
     * @Serializer\Expose()
     * @Serializer\AccessType("public_method")
     */
    protected $position;

    /**
     * Return x coordinates
     *
     * @return integer
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set x coordinates
     *
     * @param integer $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * Get y coordinates
     *
     * @return integer
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Set y coordinates
     *
     * @param integer $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * Return a position object from x and y
     *
     * @return Position
     */
    public function getPosition()
    {
        return new Position($this->x, $this->y);
    }

    /**
     * Set x and y from a position object
     *
     * @param Position $position
     */
    public function setPosition(Position $position)
    {
        $this->x = $position->x;
        $this->y = $position->y;
    }
} 
