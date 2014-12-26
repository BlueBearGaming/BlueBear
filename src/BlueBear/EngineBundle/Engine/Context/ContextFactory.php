<?php

namespace BlueBear\EngineBundle\Engine\Context;

use BlueBear\CoreBundle\Entity\Behavior\HasMapManager;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Map;
use Doctrine\Bundle\DoctrineBundle\Registry;

class ContextFactory
{
    use HasMapManager;

    /**
     * @var Registry
     */
    protected $doctrine;

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

    public function loadContext(Map $map, $contextId)
    {
        $context = $this->getDoctrine()->getRepository('BlueBearCoreBundle:Map\Context')->find($contextId);

        if (!$context) {
            throw new \Exception('Invalid context id : ' . $contextId);
        }

    }

    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     *
     *
     * @return Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }
} 