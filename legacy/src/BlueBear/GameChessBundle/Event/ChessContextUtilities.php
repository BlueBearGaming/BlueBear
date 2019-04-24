<?php

namespace BlueBear\GameChessBundle\Event;

use BlueBear\EngineBundle\Engine\ContextUtilities;
use BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest;
use BlueBear\EngineBundle\Manager\EntityInstanceManager;
use BlueBear\GameChessBundle\Entity\Piece;

class ChessContextUtilities extends ContextUtilities
{
    const MOVEMENT = 'movement';
    const CAPTURE = 'capture';
    const BLOCKED = 'blocked';

    /**
     * @var Piece[]
     */
    protected $pieces;

    /** @var EntityInstanceManager */
    protected $entityInstanceManager;

    /**
     * @param EntityInstanceManager $entityInstanceManager
     */
    public function __construct(EntityInstanceManager $entityInstanceManager)
    {
        $this->entityInstanceManager = $entityInstanceManager;
    }

    /**
     * @param Piece $piece
     * @param int $x
     * @param int $y
     * @param array $mapItems
     * @return string
     */
    public function handlePossibleAction(Piece $piece, $x, $y, array &$mapItems)
    {
        if ($this->handlePossibleMovement($piece, $x, $y, $mapItems) === self::MOVEMENT) {
            return self::MOVEMENT;
        }
        if ($this->handlePossibleCapture($piece, $x, $y, $mapItems) === self::CAPTURE) {
            return self::CAPTURE;
        }
        return self::BLOCKED;
    }

    /**
     * @param Piece $piece
     * @param int $x
     * @param int $y
     * @param array $mapItems
     * @return string
     */
    public function handlePossibleMovement(Piece $piece, $x, $y, &$mapItems)
    {
        $found = $this->findByPosition($x, $y);
        if (!$found) {
            if ($this->isOutsideOfBoard($x, $y)) {
                return self::BLOCKED;
            }
            if ($this->isKingInCheck($piece, $x, $y)) {
                return self::BLOCKED;
            }
            // Move to free position
            $mapItems[] = $this->createEventMapItem($x, $y, $this->createClickListener($piece));
            $mapItems[] = $this->createSelectionMapItem($x, $y); // @todo create pencil for 'movement'
            return self::MOVEMENT;
        }
        return self::BLOCKED;
    }

    /**
     * @param Piece $piece
     * @param int $x
     * @param int $y
     * @param array $mapItems
     * @return string
     */
    public function handlePossibleCapture(Piece $piece, $x, $y, &$mapItems)
    {
        $found = $this->findByPosition($x, $y);
        if ($found && $found->isWhite() !== $piece->isWhite()) { // If found is of the opposite color of the actual player
            if ($this->isKingInCheck($piece, $x, $y)) {
                return self::BLOCKED;
            }
            // Capture the opponent's piece
            $mapItems[] = $this->createEventMapItem($x, $y, $this->createClickListener($piece));
            $mapItems[] = $this->createSelectionMapItem($x, $y); // @todo create pencil for 'capture'
            return self::CAPTURE;
        }
        return self::BLOCKED;
    }

    /**
     * @param Piece $piece
     * @return array
     */
    protected function createClickListener(Piece $piece)
    {
        $type = str_replace('chess_', '', $piece->getType());
        $listener = ['name' => "bluebear.chess.{$type}.move"];
        if ($piece) {
            $listener['source'] = [
                'layer' => $piece->getMapItem()->getLayerName(),
                'position' => [
                    'x' => $piece->getX(),
                    'y' => $piece->getY(),
                ],
            ];
        }
        return ['click' => $listener];
    }

    /**
     * @param int $x
     * @param int $y
     * @return Piece|null
     */
    public function findByPosition($x, $y)
    {
        $this->initializePieces();
        if (isset($this->pieces[$x][$y])) {
            return $this->pieces[$x][$y];
        }
        return null;
    }

    /**
     * @param MapItemSubRequest $target
     * @return Piece|null
     */
    public function findTarget(MapItemSubRequest $target)
    {
        return $this->findByPosition($target->position->x, $target->position->y);
    }

    /**
     * @param Piece $piece
     * @return \App\Entity\Map\MapItem
     */
    public function selectPiece(Piece $piece)
    {
        return $this->createSelectionMapItem($piece->getX(), $piece->getY());
    }

    /**
     * @param Piece $piece
     * @param $x
     * @param $y
     * @return bool
     */
    public function isKingInCheck(Piece $piece, $x, $y)
    {
        // @todo check if king is checked with new move
        return false;
    }

    /**
     * @internal Used by other methods to initialize $this->pieces
     */
    protected function initializePieces()
    {
        if ($this->pieces) {
            return;
        }
        /** @var Piece[] $pieces */
        $pieces = $this->entityInstanceManager->findBy([
            'mapItem' => $this->context->getMapItems()->toArray(),
        ]);
        if (count($pieces) === 0) {
            throw new \Exception("No piece found on board");
        }
        foreach ($pieces as $piece) {
            if ($piece instanceof Piece) {
                $this->pieces[$piece->getMapItem()->getX()][$piece->getMapItem()->getY()] = $piece;
            }
        }
    }

    public function isOutsideOfBoard($x, $y)
    {
        return $x < 0 || $y < 0 || $x > 7 || $y > 7;
    }

    /**
     * @param Piece $piece
     * @param $moveX
     * @param $moveY
     * @param array $moves
     * @return array
     */
    public function findPath(Piece $piece, $moveX, $moveY, array &$moves = [])
    {
        $x = $piece->getX();
        $y = $piece->getY();
        do {
            $x += $moveX;
            $y += $moveY;
            if ($this->isOutsideOfBoard($x, $y)) {
                return $moves;
            }
            $found = $this->findByPosition($x, $y);
            if ($found && $found->isWhite() === $piece->isWhite()) {
                return $moves;
            }
            $moves[] = ['x' => $x, 'y' => $y];
        } while (!$found);
        return $moves;
    }
}
