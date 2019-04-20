<?php

namespace BlueBear\HexagonBundle\Event\Listener;

use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Manager\MapItemManager;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Request\MapItemClickRequest;
use BlueBear\EngineBundle\Event\Response\MapUpdateResponse;
use BlueBear\EngineBundle\Factory\EntityTypeFactory;
use BlueBear\HexagonBundle\Entity\Unit;
use BlueBear\HexagonBundle\Event\UnitContextUtilities;
use Doctrine\Common\Collections\Collection;

abstract class UnitEventListener
{
    /** @var UnitContextUtilities */
    protected $contextUtilities;

    /** @var MapItemManager */
    protected $mapItemManager;

    /** @var EntityTypeFactory */
    protected $entityTypeFactory; // Temporary

    /** @var MapItem[]|Collection */
    protected $mapItems = [];

    /** @var Unit */
    protected $unit;

    /**
     * @return array
     */
    abstract protected function findMoves();

    public function __construct(
        UnitContextUtilities $contextUtilities,
        MapItemManager $mapItemManager,
        EntityTypeFactory $entityTypeFactory
    ) {
        $this->contextUtilities = $contextUtilities;
        $this->mapItemManager = $mapItemManager;
        $this->entityTypeFactory = $entityTypeFactory; // Temporary
    }

    /**
     * @param EngineEvent $engineEvent
     *
     * @throws \Exception
     */
    public function onSelect(EngineEvent $engineEvent)
    {
        /**
         * @var MapItemClickRequest $request
         * @var MapUpdateResponse   $response
         */
        $request = $engineEvent->getRequest();
        $response = $engineEvent->getResponse();
        $context = $engineEvent->getContext();
        $this->contextUtilities->setContext($context);
        $this->unit = $this->contextUtilities->findTarget($request->target);
        if (!$this->unit) {
            throw new \Exception('No valid target found');
        }

        // Select current piece
        $this->mapItems = [
            $this->contextUtilities->selectUnit($this->unit),
        ];

        $this->addPossibleActions();

        $response->setData($this->mapItems);
        $response->name = EngineEvent::EDITOR_MAP_UPDATE;
    }

    /**
     * @param EngineEvent $engineEvent
     *
     * @throws \Exception
     */
    public function onMove(EngineEvent $engineEvent)
    {
        /**
         * @var MapItemClickRequest $request
         * @var MapUpdateResponse   $response
         */
        $request = $engineEvent->getRequest();
        $response = $engineEvent->getResponse();
        $context = $engineEvent->getContext();
        $this->contextUtilities->setContext($context);
        $this->unit = $this->contextUtilities->findTarget($request->source);
        if (!$this->unit) {
            throw new \Exception('No valid source found');
        }

        $x = $request->target->position->x;
        $y = $request->target->position->y;
        $this->isAllowedMovement($x, $y); // Will throw exception if not allowed

        $removed = [];
        $toCapture = $this->contextUtilities->findTarget($request->target);
        if ($toCapture) {
            $removed[] = $toCapture->getMapItem();
            $this->mapItemManager->delete($toCapture->getMapItem());
        }

        $mapItem = $this->unit->getMapItem();

        // Gros fix dégueulasse:
        $entitiesBehaviors = $this->entityTypeFactory->getEntityBehaviors();
        $behaviors = $this->unit->getBehaviors();
        // adding entity listeners for each behaviors from configuration
        foreach ($behaviors as $behavior) {
            if (!array_key_exists($behavior, $entitiesBehaviors)) {
                throw new \Exception('Invalid behavior : '.$behavior);
            }
            $mapItem->addListener(
                'click',
                [
                    'name' => $entitiesBehaviors[$behavior]->getListener(),
                ]
            );
        }
        // Fin du gros fix dégueulasse

        $mapItem->setPath([['x' => $x, 'y' => $y]]);
        $response->setData([clone $mapItem], $removed);
        $response->name = EngineEvent::EDITOR_MAP_UPDATE;

        $mapItem->setX($x);
        $mapItem->setY($y);
        $this->mapItemManager->save($mapItem);
    }

    /**
     * Check all possible actions for legal moves
     */
    protected function addPossibleActions()
    {
        foreach ($this->findMoves() as $position) {
            $this->handlePossibleAction($position['x'], $position['y']);
        }
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return string
     */
    protected function handlePossibleAction($x, $y)
    {
        if (!$this->isAllowedMovement($x, $y)) {
            return UnitContextUtilities::BLOCKED;
        }

        return $this->contextUtilities->handlePossibleAction($this->unit, $x, $y, $this->mapItems);
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return string
     */
    protected function handlePossibleCapture($x, $y)
    {
        if (!$this->isAllowedMovement($x, $y)) {
            return UnitContextUtilities::BLOCKED;
        }

        return $this->contextUtilities->handlePossibleCapture($this->unit, $x, $y, $this->mapItems);
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return string
     */
    protected function handlePossibleMovement($x, $y)
    {
        if (!$this->isAllowedMovement($x, $y)) {
            return UnitContextUtilities::BLOCKED;
        }

        return $this->contextUtilities->handlePossibleMovement($this->unit, $x, $y, $this->mapItems);
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return bool
     */
    protected function isAllowedMovement($x, $y)
    {
        foreach ($this->findMoves() as $move) {
            if ($move['x'] == $x && $move['y'] == $y) {
                // More checks with ChessContextUtilities
                return true;
            }
        }

        return false;
    }

    /**
     * Method is declared here because it's used by both rook and queen
     *
     * @param array $moves
     *
     * @return array
     */
    protected function findRookMoves(array &$moves = [])
    {
        $this->contextUtilities->findPath($this->unit, 1, 0, $moves);
        $this->contextUtilities->findPath($this->unit, -1, 0, $moves);
        $this->contextUtilities->findPath($this->unit, 0, 1, $moves);
        $this->contextUtilities->findPath($this->unit, 0, -1, $moves);

        return $moves;
    }

    /**
     * Method is declared here because it's used by both bishop and queen
     *
     * @param array $moves
     *
     * @return array
     */
    protected function findBishopMoves(array &$moves = [])
    {
        $this->contextUtilities->findPath($this->unit, 1, 1, $moves);
        $this->contextUtilities->findPath($this->unit, 1, -1, $moves);
        $this->contextUtilities->findPath($this->unit, -1, 1, $moves);
        $this->contextUtilities->findPath($this->unit, -1, -1, $moves);

        return $moves;
    }
}
