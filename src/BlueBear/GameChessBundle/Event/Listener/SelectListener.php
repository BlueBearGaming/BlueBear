<?php

namespace BlueBear\GameChessBundle\Event\Listener;

use BlueBear\EngineBundle\Engine\ContextUtilities;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest;
use BlueBear\EngineBundle\Event\Response\MapUpdateResponse;
use BlueBear\GameChessBundle\Event\Request\SelectRequest;

class SelectListener
{
    /** @var ContextUtilities */
    protected $contextUtilities;

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

        $this->contextUtilities->setContext($context);

        // Select current piece
        $mapItems = [
            $this->contextUtilities->selectTarget($request->target),
        ];

        $x = $request->target->position->x;
        $y = $request->target->position->y;
        if (true) { // @todo Player is white
            $y--;
        } else {
            $y++;
        }
        $found = $context->getMapItemByLayerNameAndPosition($request->target->layer, $x, $y);
        if (!$found) {
            // Move to free position
            $mapItems[] = $this->contextUtilities->createEventMapItem($x, $y, $this->createClickListener('chess.move', $request->target));
            $mapItems[] = $this->contextUtilities->createSelectionMapItem($x, $y); // @todo create pencil for 'movement'
        } elseif ($found) { // @todo If found is of the opposite color of the actual player
            // Capture the opponent's piece
            $mapItems[] = $this->contextUtilities->createEventMapItem($x, $y, $this->createClickListener('chess.capture', $request->target));
            $mapItems[] = $this->contextUtilities->createSelectionMapItem($x, $y); // @todo create pencil for 'capture'
        }

        $response->setData($mapItems);
        $response->name = EngineEvent::EDITOR_MAP_UPDATE;
    }

    protected function createClickListener($name, MapItemSubRequest $source = null)
    {
        $listener = [
            'name' => $name,
        ];
        if ($source) {
            $listener['source'] = $source;
        }
        return [
            'click' => $listener,
        ];
    }
}
