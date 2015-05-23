<?php

namespace BlueBear\GameChessBundle\Event\Listener;


class RookEventListener extends PieceEventListener
{
    protected function findMoves()
    {
        return $this->findRookMoves();
    }
}
