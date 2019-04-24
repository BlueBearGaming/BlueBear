<?php

namespace BlueBear\GameChessBundle\Event\Listener;


class QueenEventListener extends PieceEventListener
{
    protected function findMoves()
    {
        $moves = [];
        $this->findBishopMoves($moves);
        $this->findRookMoves($moves);
        return $moves;
    }
}
