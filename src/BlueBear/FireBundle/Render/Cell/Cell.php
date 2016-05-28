<?php

namespace BlueBear\FireBundle\Render\Cell;

class Cell
{
    /**
     * @var int
     */
    protected $x;

    /**
     * @var int
     */
    protected $y;

    /**
     * @var int
     */
    protected $id;

    /**
     * Cell constructor.
     *
     * @param $x
     * @param $y
     * @param $id
     */
    public function __construct($x, $y, $id)
    {
        $this->x = $x;
        $this->y = $y;
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
