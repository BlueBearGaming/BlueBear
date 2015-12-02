<?php

namespace BlueBear\FireBundle\Render\Grid;


class GridBuilder
{
    public function build()
    {
        $configuration = $this->getCellConfiguration();
        $cells = [];

        foreach ($configuration as $x => $row) {
            foreach ($row as $y => $cell) {
                $cells[$x][$y] = new Cell($x, $y);
            }
        }
        return new Grid($cells);
    }

    protected function getCellConfiguration()
    {
        return [
            [
                [],
                [],
                []
            ],
            [
                [],
                [],
                []
            ],
        ];
    }
}
