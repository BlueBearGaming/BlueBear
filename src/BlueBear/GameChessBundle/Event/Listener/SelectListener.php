<?php

namespace BlueBear\GameChessBundle\Event\Listener;

use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\EngineBundle\Engine\ContextUtilities;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest;
use BlueBear\EngineBundle\Event\Response\MapUpdateResponse;
use BlueBear\GameChessBundle\Event\ChessContextUtilities;
use BlueBear\GameChessBundle\Event\Request\SelectRequest;
use Doctrine\Common\Collections\Collection;

class SelectListener
{
    /** @var ChessContextUtilities */
    protected $contextUtilities;

    /** @var  MapItem[]|Collection */
    protected $mapItems = [];

    public function __construct(ContextUtilities $contextUtilities)
    {
        $this->contextUtilities = $contextUtilities;
    }


    public function onSelect(EngineEvent $engineEvent)
    {
        /**
         * @var SelectRequest $request
         * @var MapUpdateResponse $response
         */
        $request = $engineEvent->getRequest();
        $response = $engineEvent->getResponse();
        $context = $engineEvent->getContext();
        $target = $request->target;

        $this->contextUtilities->setContext($context);

        // Select current piece
        $this->mapItems = [
            $this->contextUtilities->selectTarget($target),
        ];

        $this->addPossibleMovements($target);
        $this->addPossibleCaptures($target);

        $response->setData($this->mapItems);
        $response->name = EngineEvent::EDITOR_MAP_UPDATE;
    }

    protected function addPossibleMovements(MapItemSubRequest $target)
    {
        $x = $target->position->x;
        $y = $target->position->y;
        if ($this->contextUtilities->findTarget($target)->isWhite()) {
            $y--;
        } else {
            $y++;
        }
        $this->contextUtilities->handlePossibleMovement($target, $x, $y, $this->mapItems);
    }

    protected function addPossibleCaptures(MapItemSubRequest $target)
    {
        $x = $target->position->x;
        $y = $target->position->y;
        if ($this->contextUtilities->findTarget($target)->isWhite()) {
            $y--;
        } else {
            $y++;
        }
        $this->contextUtilities->handlePossibleCapture($target, $x+1, $y, $this->mapItems);
        $this->contextUtilities->handlePossibleCapture($target, $x-1, $y, $this->mapItems);
    }
}
