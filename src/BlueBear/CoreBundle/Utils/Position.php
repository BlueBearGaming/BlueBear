<?php

namespace BlueBear\CoreBundle\Utils;

use JMS\Serializer\Annotation as Serializer;

/**
 * Position object for serialization
 */
class Position
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     *
     * @var int
     */
    public $x;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     *
     * @var int
     */
    public $y;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     *
     * @var int
     */
    public $z;

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     */
    public function __construct($x = 0, $y = 0, $z = 0)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->x.'_'.$this->y.'_'.$this->z;
    }
}
