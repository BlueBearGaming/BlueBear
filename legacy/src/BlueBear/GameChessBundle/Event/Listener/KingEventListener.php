<?php

namespace BlueBear\GameChessBundle\Event\Listener;


class KingEventListener extends PieceEventListener
{
    /**
     * @return array
     */
    protected function findMoves()
    {
        $moves = [];
        for ($x = -1; $x < 2; $x++) {
            for ($y = -1; $y < 2; $y++) {
                if ($x == 0 && $y == 0) {
                    continue;
                }
                $moves[] = ['x' => $this->piece->getX() + $x, 'y' => $this->piece->getY() + $y];
            }
        }
        return $moves;
    }
}
