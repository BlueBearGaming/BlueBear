<?php

namespace BlueBear\GameChessBundle\Event\Listener;

use BlueBear\EngineBundle\Engine\Utils\MapUtils;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Response\MapUpdateResponse;
use BlueBear\GameChessBundle\Event\Request\SelectRequest;

class SelectListener
{
    public function onSelect(EngineEvent $engineEvent)
    {
        /**
         * @var SelectRequest $request
         * @var MapUpdateResponse $response
         */
        $request = $engineEvent->getRequest();
        $response = $engineEvent->getResponse();

        $utils = new MapUtils();
        $mapItems = $utils->getSelectionForTarget($request->target);

        $response->setData($mapItems, []);
        $response->name = EngineEvent::EDITOR_MAP_UPDATE;
    }
}
