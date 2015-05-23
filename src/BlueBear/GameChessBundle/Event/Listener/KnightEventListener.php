<?php

namespace BlueBear\GameChessBundle\Event\Listener;


class KnightEventListener extends PieceEventListener
{
    protected function findMoves()
    {
        $x = $this->piece->getX();
        $y = $this->piece->getY();
        return [
            ['x' => $x+2, 'y' => $y+1],
            ['x' => $x+2, 'y' => $y-1],
            ['x' => $x-2, 'y' => $y+1],
            ['x' => $x-2, 'y' => $y-1],
            ['x' => $x+1, 'y' => $y+2],
            ['x' => $x+1, 'y' => $y-2],
            ['x' => $x-1, 'y' => $y+2],
            ['x' => $x-1, 'y' => $y-2],
        ];
    }
}
