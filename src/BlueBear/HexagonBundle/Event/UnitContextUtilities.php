<?php

namespace BlueBear\HexagonBundle\Event;

use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\EngineBundle\Engine\ContextUtilities;
use BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest;
use BlueBear\EngineBundle\Manager\EntityInstanceManager;
use BlueBear\HexagonBundle\Entity\Unit;

/**
 * Handles base logic for all units
 */
class UnitContextUtilities extends ContextUtilities
{
    const MOVEMENT = 'movement';
    const CAPTURE = 'capture';
    const BLOCKED = 'blocked';

    /**
     * @var Unit[]
     */
    protected $units;

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
     * @param Unit  $unit
     * @param int   $x
     * @param int   $y
     * @param array $mapItems
     *
     * @return string
     */
    public function handlePossibleAction(Unit $unit, $x, $y, array &$mapItems)
    {
        if ($this->handlePossibleMovement($unit, $x, $y, $mapItems) === self::MOVEMENT) {
            return self::MOVEMENT;
        }
        if ($this->handlePossibleCapture($unit, $x, $y, $mapItems) === self::CAPTURE) {
            return self::CAPTURE;
        }

        return self::BLOCKED;
    }

    /**
     * @param Unit  $unit
     * @param int   $x
     * @param int   $y
     * @param array $mapItems
     *
     * @return string
     */
    public function handlePossibleMovement(Unit $unit, $x, $y, &$mapItems)
    {
        $found = $this->findByPosition($x, $y);
        if (!$found) {
            if ($this->isOutsideOfBoard($x, $y)) {
                return self::BLOCKED;
            }
            // Move to free position
            $mapItems[] = $this->createEventMapItem($x, $y, $this->createClickListener($unit));
            $mapItems[] = $this->createSelectionMapItem($x, $y); // @todo create pencil for 'movement'

            return self::MOVEMENT;
        }

        return self::BLOCKED;
    }

    /**
     * @param Unit  $unit
     * @param int   $x
     * @param int   $y
     * @param array $mapItems
     *
     * @return string
     */
    public function handlePossibleCapture(Unit $unit, $x, $y, &$mapItems)
    {
        $found = $this->findByPosition($x, $y);
        if ($found) { // If found is of the opposite color of the actual player
            // Capture the opponent's Unit
            $mapItems[] = $this->createEventMapItem($x, $y, $this->createClickListener($unit));
            $mapItems[] = $this->createSelectionMapItem($x, $y); // @todo create pencil for 'capture'

            return self::CAPTURE;
        }

        return self::BLOCKED;
    }

    /**
     * @param Unit $unit
     *
     * @return array
     */
    protected function createClickListener(Unit $unit)
    {
        $type = str_replace('hexagon_', '', $unit->getType());
        $listener = ['name' => "bluebear.hexagon.{$type}.move"];
        if ($unit) {
            $listener['source'] = [
                'layer' => $unit->getMapItem()->getLayerName(),
                'position' => [
                    'x' => $unit->getX(),
                    'y' => $unit->getY(),
                ],
            ];
        }

        return ['click' => $listener];
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return Unit|null
     */
    public function findByPosition($x, $y)
    {
        $this->initializeunits();
        if (isset($this->units[$x][$y])) {
            return $this->units[$x][$y];
        }

        return null;
    }

    /**
     * @param MapItemSubRequest $target
     *
     * @return Unit|null
     */
    public function findTarget(MapItemSubRequest $target)
    {
        return $this->findByPosition($target->position->x, $target->position->y);
    }

    /**
     * @param Unit $unit
     *
     * @return MapItem
     */
    public function selectUnit(Unit $unit)
    {
        return $this->createSelectionMapItem($unit->getX(), $unit->getY());
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return bool
     */
    public function isOutsideOfBoard($x, $y)
    {
        return false; // @todo check if background is acceptable
    }

    /**
     * @param Unit  $unit
     * @param int   $moveX
     * @param int   $moveY
     * @param array $moves
     *
     * @return array
     */
    public function findPath(Unit $unit, $moveX, $moveY, array &$moves = [])
    {
        $x = $unit->getX();
        $y = $unit->getY();
        do {
            $x += $moveX;
            $y += $moveY;
            if ($this->isOutsideOfBoard($x, $y)) {
                return $moves;
            }
            $found = $this->findByPosition($x, $y);
            if ($found) {
                return $moves;
            }
            $moves[] = ['x' => $x, 'y' => $y];
        } while (!$found);

        return $moves;
    }

    /**
     * @internal Used by other methods to initialize $this->units
     */
    protected function initializeunits()
    {
        if ($this->units) {
            return;
        }
        /** @var Unit[] $units */
        $units = $this->entityInstanceManager->findBy(
            [
                'mapItem' => $this->context->getMapItems()->toArray(),
            ]
        );
        foreach ($units as $unit) {
            if ($unit instanceof Unit) {
                $this->units[$unit->getMapItem()->getX()][$unit->getMapItem()->getY()] = $unit;
            }
        }
    }
}
