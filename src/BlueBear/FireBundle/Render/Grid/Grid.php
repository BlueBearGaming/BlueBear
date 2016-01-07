<?php

namespace BlueBear\FireBundle\Render\Grid;

use BlueBear\FireBundle\Render\Cell\Cell;
use BlueBear\FireBundle\Render\Fire\Fire;
use BlueBear\FireBundle\Render\Fireman\Fireman;

class Grid
{
    /**
     * @var Cell[]
     */
    protected $cells = [];

    /**
     * @var Fireman
     */
    protected $fireman;

    /**
     * @var Fire[]
     */
    protected $fires;

    /**
     * Grid constructor.
     *
     * @param Cell[] $cells
     * @param Fireman $fireman
     * @param Fire[] $fires
     */
    public function __construct($cells, Fireman $fireman, $fires)
    {
        $this->cells = $cells;
        $this->fireman = $fireman;
        $this->fires = $fires;
    }

    /**
     * @return Cell[]
     */
    public function getCells()
    {
        return $this->cells;
    }

    /**
     * @return Fireman
     */
    public function getFireman()
    {
        return $this->fireman;
    }

    /**
     * @return Fire[]
     */
    public function getFires()
    {
        return $this->fires;
    }

    /**
     * @param $x
     * @param $y
     * @return bool
     */
    public function getFire($x, $y)
    {
        $fire = false;

        if (!empty($this->fires[$x][$y])) {
            $fire = $this->fires[$x][$y];
        }
        return $fire;
    }
}
