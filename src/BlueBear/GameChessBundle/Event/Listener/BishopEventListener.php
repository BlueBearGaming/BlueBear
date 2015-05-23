<?php

namespace BlueBear\GameChessBundle\Event\Listener;


class BishopEventListener extends PieceEventListener
{
    protected function findMoves()
    {
        return $this->findBishopMoves();
    }
}
