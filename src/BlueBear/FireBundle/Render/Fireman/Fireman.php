<?php

namespace BlueBear\FireBundle\Render\Fireman;


class Fireman
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
     * Fireman constructor.
     *
     * @param int $x
     * @param int $y
     * @param null $id
     */
    public function __construct($x, $y, $id = null)
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
