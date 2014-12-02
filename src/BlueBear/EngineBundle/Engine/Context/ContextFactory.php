<?php

namespace BlueBear\EngineBundle\Engine\Context;

use BlueBear\CoreBundle\Entity\Behavior\HasMapManager;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Map;

class ContextFactory
{
    use HasMapManager;

    /**
     * Create a default context from map data
     *
     * @param Map $map
     * @return Context
     */
    public function create(Map $map)
    {
        $context = new Context();
        $context->setLabel('Initial context');
        $context->setMap($map);
        // adding context to map
        $map->getContexts()->add($context);
        $this->getMapManager()->save($context);
        $this->getMapManager()->save($map);
        // set context as current context
        $map->setCurrentContext($context);

        return $context;
    }
} 