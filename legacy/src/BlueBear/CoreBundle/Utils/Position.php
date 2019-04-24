<?php

namespace App\Utils;

use JMS\Serializer\Annotation as Serializer;

class Position
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     * @var int
     */
    public $x;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     * @var int
     */
    public $y;

    /**
     * @param int $x
     * @param int $y
     */
    public function __construct($x = 0, $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getId()
    {
        return $this->x . '_' . $this->y;
    }
}
