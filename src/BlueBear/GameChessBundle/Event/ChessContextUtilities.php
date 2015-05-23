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
     * @param MapItemSubRequest $target
     * @param int $x
     * @param int $y
     * @param array $mapItems
     * @return string
     */
    public function handlePossibleAction(MapItemSubRequest $target, $x, $y, array &$mapItems)
    {
        if ($this->handlePossibleMovement($target, $x, $y, $mapItems) === self::MOVEMENT) {
            return self::MOVEMENT;
        }
        if ($this->handlePossibleCapture($target, $x, $y, $mapItems) === self::CAPTURE) {
            return self::CAPTURE;
        }
        return self::BLOCKED;
    }

    /**
     * @param MapItemSubRequest $target
     * @param int $x
     * @param int $y
     * @param array $mapItems
     * @return string
     */
    public function handlePossibleMovement(MapItemSubRequest $target, $x, $y, &$mapItems)
    {
        $found = $this->findByPosition($x, $y);
        if (!$found) {
            if ($this->isKingChecked($target, $x, $y)) {
                return self::BLOCKED;
            }
            // Move to free position
            $mapItems[] = $this->createEventMapItem($x, $y, $this->createClickListener('chess.move', $target));
            $mapItems[] = $this->createSelectionMapItem($x, $y); // @todo create pencil for 'movement'
            return self::MOVEMENT;
        }
        return self::BLOCKED;
    }

    /**
     * @param MapItemSubRequest $target
     * @param int $x
     * @param int $y
     * @param array $mapItems
     * @return string
     */
    public function handlePossibleCapture(MapItemSubRequest $target, $x, $y, &$mapItems)
    {
        $initial = $this->findTarget($target);
        $found = $this->findByPosition($x, $y);
        if ($found && $found->isWhite() !== $initial->isWhite()) { // If found is of the opposite color of the actual player
            if ($this->isKingChecked($target, $x, $y)) {
                return self::BLOCKED;
            }
            // Capture the opponent's piece
            $mapItems[] = $this->createEventMapItem($x, $y, $this->createClickListener('chess.capture', $target));
            $mapItems[] = $this->createSelectionMapItem($x, $y); // @todo create pencil for 'capture'
            return self::CAPTURE;
        }
        return self::BLOCKED;
    }

    /**
     * @param string $name
     * @param MapItemSubRequest $source
     * @return array
     */
    protected function createClickListener($name, MapItemSubRequest $source = null)
    {
        $listener = ['name' => $name];
        if ($source) {
            $listener['source'] = $source;
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

    public function findTarget(MapItemSubRequest $target)
    {
        return $this->findByPosition($target->position->x, $target->position->y);
    }

    /**
     * @param $target
     * @param $x
     * @param $y
     * @return bool
     */
    public function isKingChecked($target, $x, $y)
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
        foreach ($pieces as $piece) {
            if ($piece instanceof Piece) {
                $this->pieces[$piece->getMapItem()->getX()][$piece->getMapItem()->getY()] = $piece;
            }
        }
    }
}