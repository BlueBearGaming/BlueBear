<?php

namespace BlueBear\EngineBundle\Event\Response;

use App\Entity\Map\Context;
use BlueBear\EngineBundle\Event\EventResponse;

class MapLoadResponse extends EventResponse
{
    public function setData(Context $context)
    {
        $this->data = $context;
    }
}
