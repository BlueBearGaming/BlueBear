<?php

namespace BlueBear\GameChessBundle\Event\Listener;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Engine\Utils\MapUtils;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\GameChessBundle\Event\Request\SelectRequest;
use BlueBear\GameChessBundle\Event\Response\SelectResponse;

class SelectListener
{
    public function onSelect(EngineEvent $engineEvent)
    {
        /**
         * @var SelectRequest $request
         * @var SelectResponse $response
         */
        $request = $engineEvent->getRequest();
        $response = $engineEvent->getResponse();

        $utils = new MapUtils();
        $mapItems = $utils->getMapItemForSelection(new Position(0, 0), Constant::LAYER_TYPE_UNIT, new Position(1, 1));

        $response->setData($mapItems);

    }
}
