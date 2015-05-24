<?php

namespace BlueBear\EngineBundle\Listener;

use Symfony\Component\HttpKernel\Event\PostResponseEvent;

class KernelTerminateEventListener
{
    protected $callbacks = [];


    public function terminate(PostResponseEvent $postResponseEvent)
    {
        foreach ($this->callbacks as $callback) {
            if (is_callable($callback)) {
                $callback($postResponseEvent);
            }
        }
    }

    public function addCallBack(callable $callback)
    {
        $this->callbacks[] = $callback;
    }
}