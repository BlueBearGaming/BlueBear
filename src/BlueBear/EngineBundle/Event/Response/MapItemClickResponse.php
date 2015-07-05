<?php

namespace BlueBear\EngineBundle\Event\Response;

use BlueBear\EngineBundle\Event\EventResponse;

class MapItemClickResponse extends EventResponse
{
    public function setData($mapItems)
    {
        $this->data['updated'] = $mapItems;
    }

    public function setMoved($mapItems)
    {
        $this->data['moved'] = $mapItems;
    }
}
