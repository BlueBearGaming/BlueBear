<?php

namespace BlueBear\GameChessBundle\Event\Listener;


class PawnEventListener extends PieceEventListener
{
    protected function addPossibleActions()
    {
        $x = $this->piece->getX();
        $y = $this->piece->getY();
        $this->piece->isWhite() ? $y-- : $y++; // Pawn only moves to the opposite side of the board
        $this->handlePossibleMovement($x, $y);

        // Adding possible captures in diagonal
        $this->handlePossibleCapture($x + 1, $y);
        $this->handlePossibleCapture($x - 1, $y);

        // If pawn has never moved, it can move up to the second row
        if (($this->piece->getY() == 6 && $this->piece->isWhite())
            || ($this->piece->getY() == 1 && !$this->piece->isWhite())
        ) {
            $this->piece->isWhite() ? $y-- : $y++;
            $this->handlePossibleMovement($x, $y);
        }

        // @todo take "en passant"
    }

    protected function findMoves()
    {
        $moves = [];
        $x = $this->piece->getX();
        $y = $this->piece->getY();
        $this->piece->isWhite() ? $y-- : $y++; // Pawn only moves to the opposite side of the board
        $moves[] = ['x' => $x, 'y' => $y];
        $moves[] = ['x' => $x + 1, 'y' => $y];
        $moves[] = ['x' => $x - 1, 'y' => $y];

        // If pawn has never moved, it can move up to the second row
        if (($this->piece->getY() == 6 && $this->piece->isWhite())
            || ($this->piece->getY() == 1 && !$this->piece->isWhite())
        ) {
            $this->piece->isWhite() ? $y-- : $y++;
            $moves[] = ['x' => $x, 'y' => $y];
        }

        // @todo take "en passant"
        return $moves;
    }
}
