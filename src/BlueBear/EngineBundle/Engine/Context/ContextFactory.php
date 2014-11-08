<?php

namespace BlueBear\EngineBundle\Engine\Context;

use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Map;

class ContextFactory
{
    /**
     * Create a default context from map data
     *
     * @param Map $map
     */
    public function create(Map $map)
    {
        $context = new Context();
        $context->setMap($map);


    }
} 