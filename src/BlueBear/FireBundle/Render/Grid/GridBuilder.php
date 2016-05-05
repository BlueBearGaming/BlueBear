<?php

namespace BlueBear\FireBundle\Render\Grid;

use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\FireBundle\Render\Cell\Cell;
use BlueBear\FireBundle\Render\Fire\Fire;
use BlueBear\FireBundle\Render\Fireman\Fireman;

class GridBuilder
{
    /**
     * @var Map
     */
    protected $map;

    /**
     * GridBuilder constructor.
     * 
     * @param Map $map
     */
    public function __construct(Map $map)
    {
        $this->map = $map;
    }

    public function build()
    {
        $cells = [];
        $x = 0;
        $rowCount = 5;
        $columnCount = 5;

        while ($x < $rowCount) {
            $y = 0;

            while ($y < $columnCount) {
                $cells[$x][$y] = new Cell($x, $y);
                $y++;
            }
            $x++;
        }
        return new Grid($cells, $this->getFireman(), $this->getFires());
    }

    protected function getFireman()
    {
        return new Fireman(0, 0);
    }

    protected function getFires()
    {
        $fires[4][4] = new Fire(4, 4);

        return $fires;
    }
}
