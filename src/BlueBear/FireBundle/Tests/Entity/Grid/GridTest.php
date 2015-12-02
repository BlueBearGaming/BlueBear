<?php

namespace BlueBear\FireBundle\Tests\Entity\Grid;

use BlueBear\FireBundle\Render\Grid\Grid;
use PHPUnit_Framework_TestCase;

class GridTest extends PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $grid = new Grid();
        $cells = $grid->getCells();

        foreach ($cells as $cell) {
            $this->assertInternalType('integer', gettype($cell->getX()));
            $this->assertInternalType('integer', gettype($cell->getY()));
        }
    }
}
