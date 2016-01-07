<?php

namespace BlueBear\FireBundle\Tests\Entity\Grid;

use BlueBear\FireBundle\Render\Cell\Cell;
use BlueBear\FireBundle\Render\Fire\Fire;
use BlueBear\FireBundle\Render\Fireman\Fireman;
use BlueBear\FireBundle\Render\Grid\Grid;
use PHPUnit_Framework_TestCase;

class GridTest extends PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $grid = new Grid([
            new Cell(0, 0),
            new Cell(0, 1),
        ], new Fireman(0, 0), [
            0 => [
                1 => new Fire(0, 1)
            ]
        ]);
        $cells = $grid->getCells();

        foreach ($cells as $cell) {
            $this->assertInternalType('integer', $cell->getX());
            $this->assertInternalType('integer', $cell->getY());
        }
    }
}
