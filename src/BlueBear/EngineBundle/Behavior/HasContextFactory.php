<?php

namespace BlueBear\EngineBundle\Behavior;

use BlueBear\EngineBundle\Engine\Context\ContextFactory;

trait HasContextFactory
{
    /**
     * @var ContextFactory
     */
    protected $contextFactory;

    /**
     * @return ContextFactory
     */
    public function getContextFactory()
    {
        return $this->contextFactory;
    }

    /**
     * @param ContextFactory $contextFactory
     */
    public function setContextFactory($contextFactory)
    {
        $this->contextFactory = $contextFactory;
    }
} 