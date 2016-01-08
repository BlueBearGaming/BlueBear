<?php

namespace BlueBear\EngineBundle\Event;


interface EngineEventInterface
{
    /**
     * EngineEvent constructor.
     *
     * @param EventRequest $request
     * @param EventResponse $response
     */
    public function __construct(EventRequest $request, EventResponse $response);

    /**
     * @return EventRequest
     */
    public function getRequest();

    /**
     * @return EventResponse
     */
    public function getResponse();
}
