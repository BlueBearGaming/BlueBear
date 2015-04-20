<?php

namespace BlueBear\EngineBundle\Event\Response;

use BlueBear\EngineBundle\Event\EventResponse;

class MapItemClickResponse extends EventResponse
{
    public function setData($mapItems)
    {
        $this->data = [
            'mapItems' => $mapItems
        ];
    }
}
