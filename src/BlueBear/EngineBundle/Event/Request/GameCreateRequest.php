<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;

class GameCreateRequest extends EventRequest
{
    public $fightersIdsByPlayer = [];
}
