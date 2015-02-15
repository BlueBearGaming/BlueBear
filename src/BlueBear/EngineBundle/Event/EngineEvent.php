<?php

namespace BlueBear\EngineBundle\Event;

use BlueBear\CoreBundle\Entity\Map\Context;
use Symfony\Component\EventDispatcher\Event;

class EngineEvent extends Event
{
    /**
     * Event name
     *
     * @var string
     */
    protected $name;

    /**
     * Event request data
     *
     * @var EventRequest
     */
    protected $request;

    /**
     * Event response data to be sent to the render engine
     *
     * @var EventResponse
     */
    protected $response;

    /**
     * @var Context
     */
    protected $context;

    /**
     * Engine events
     */
    const ENGINE_ON_ENGINE_EVENT = 'bluebear.engine.onEngineEvent';

    /**
     * Map events
     */
    const ENGINE_ON_CONTEXT_LOAD = 'bluebear.engine.onMapLoad';
    const ENGINE_ON_MAP_SAVE = 'bluebear.engine.onMapSave';

    /**
     * MapItem events
     */
    const ENGINE_ON_BEFORE_LEAVE = 'bluebear.engine.onBeforeLeave';
    const ENGINE_ON_LEAVE = 'bluebear.engine.onLeave';
    const ENGINE_ON_AFTER_LEAVE = 'bluebear.engine.onAfterLeave';
    const ENGINE_ON_BEFORE_ENTER = 'bluebear.engine.onBeforeEnter';
    const ENGINE_ON_ENTER = 'bluebear.engine.onEnter';
    const ENGINE_ON_AFTER_ENTER = 'bluebear.engine.onAfterEnter';
    const ENGINE_ON_MAP_ITEM_CLICK = 'bluebear.engine.onMapItemClick';
    const ENGINE_ON_MAP_PUT_ENTITY = 'bluebear.engine.onMapPutEntity';

    /**
     * Engine event response code
     */
    const ENGINE_EVENT_RESPONSE_OK = 'ok';
    const ENGINE_EVENT_RESPONSE_KO = 'error';

    /**
     * Return allowed events to be called in front
     *
     * @return array
     */
    public static function getAllowedEvents()
    {
        return [
            self::ENGINE_ON_MAP_ITEM_CLICK,
            self::ENGINE_ON_CONTEXT_LOAD,
            self::ENGINE_ON_MAP_SAVE,
            self::ENGINE_ON_MAP_PUT_ENTITY
        ];
    }

    /**
     * @return EventRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param EventRequest $request
     */
    public function setRequest(EventRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Return event response
     *
     * @return EventResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set event response
     *
     * @param EventResponse $response
     */
    public function setResponse(EventResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Context $context
     */
    public function setContext(Context $context)
    {
        $this->context = $context;
    }
}