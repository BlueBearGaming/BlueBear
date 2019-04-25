<?php


namespace App\Entity\Behavior;

use Symfony\Component\EventDispatcher\EventDispatcher;

trait HasEventDispatcher
{
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Return the EventDispatcher
     *
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcher $eventDispatcher
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }
} 
