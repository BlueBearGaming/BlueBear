<?php

namespace BlueBear\EngineBundle\Engine\Context;

use App\Behavior\DoctrineTrait;
use App\Entity\Behavior\HasMapManager;
use App\Entity\Map\Context;
use App\Entity\Map\Map;

class ContextFactory
{
    use HasMapManager, DoctrineTrait;

    /**
     * Create a initial context from map data
     *
     * @param Map $map
     * @return Context
     */
    public function create(Map $map)
    {
        // create initial context
        $context = new Context();
        $context->setLabel('Initial context');
        $context->setMap($map);
        // adding context to map
        $map->getContexts()->add($context);
        // set context as current context
        $map->setCurrentContext($context);
        $this->getMapManager()->save($context);
        $this->getMapManager()->save($map);

        return $context;
    }

    /**
     * @param Map $map Current map
     * @param array $mapItems
     * @return Context
     */
    public function update(Map $map, array $mapItems)
    {
        $context = new Context();
        $context->setLabel($this->generateContextName());
        $context->setMapItems($mapItems);
        $context->setMap($map);
        $this->getMapManager()->save($context);
        $map->setCurrentContext($context);
        $this->getMapManager()->save($map);

        return $context;
    }

//    public function loadContext(Map $map, $contextId)
//    {
//        $context = $this->getDoctrine()->getRepository('BlueBearCoreBundle:Map\Context')->find($contextId);
//
//        if (!$context) {
//            throw new \Exception('Invalid context id : ' . $contextId);
//        }
//
//    }

    /**
     * Generate unique context name
     *
     * @return string
     */
    protected function generateContextName()
    {
        return uniqid('context_', true);
    }
} 
