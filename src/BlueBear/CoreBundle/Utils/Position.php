<?php

namespace BlueBear\CoreBundle\Utils;

use JMS\Serializer\Annotation as Serializer;

class Position
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     * @var int
     */
    protected $x;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     * @var int
     */
    protected $y;

    /**
     * @param int $x
     * @param int $y
     */
    public function __construct($x = 0, $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }
}