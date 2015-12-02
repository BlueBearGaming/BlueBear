<?php

namespace BlueBear\FireBundle\Render\Grid;

class Grid
{
    /**
     * @var Cell[]
     */
    protected $cells = [];

    /**
     * Grid constructor.
     *
     * @param Cell[] $cells
     */
    public function __construct($cells = [])
    {
        $this->cells = $cells;
    }

    /**
     * @return Cell[]
     */
    public function getCells()
    {
        return $this->cells;
    }
}
