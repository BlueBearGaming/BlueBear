<?php

namespace BlueBear\EngineBundle\Event\Response;

use BlueBear\EngineBundle\Event\EventResponse;

class GameTurnResponse extends EventResponse
{
    public function setData(array $data)
    {
        $this->data = $data;
    }
}
