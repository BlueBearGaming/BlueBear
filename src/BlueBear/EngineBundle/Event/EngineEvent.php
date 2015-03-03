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
     * Origin event name (in case of sub-event)
     *
     * @var string
     */
    protected $originEventName;

    /**
     * Event request (read-only, set on constructor)
     *
     * @var EventRequest
     */
    protected $request;

    /**
     * Event response (read-only, set on constructor)
     *
     * @var EventResponse
     */
    protected $response;

    /**
     * @var Context
     */
    protected $context;

    /**
     * Engine events (private)
     */
    const ENGINE_ON_ENGINE_EVENT = 'bluebear.engine.engineEvent';
    const ENGINE_MAP_SAVE = 'bluebear.engine.onMapSave';

    /**
     * Map events
     */
    const ENGINE_MAP_LOAD = 'bluebear.engine.mapLoad';

    /**
     * MapItem events
     */
//    const ENGINE_ON_BEFORE_LEAVE = 'bluebear.engine.onBeforeLeave';
//    const ENGINE_ON_LEAVE = 'bluebear.engine.onLeave';
//    const ENGINE_ON_AFTER_LEAVE = 'bluebear.engine.onAfterLeave';
//    const ENGINE_ON_BEFORE_ENTER = 'bluebear.engine.onBeforeEnter';
//    const ENGINE_ON_ENTER = 'bluebear.engine.onEnter';
//    const ENGINE_ON_AFTER_ENTER = 'bluebear.engine.onAfterEnter';
    const ENGINE_MAP_ITEM_CLICK = 'bluebear.engine.mapItemClick';
    const ENGINE_MAP_PUT_ENTITY = 'bluebear.engine.mapPutEntity';

    const EDITOR_MAP_PUT_PENCIL = 'bluebear.editor.putPencil';
    const EDITOR_MAP_UPDATE = 'bluebear.editor.mapUpdate';

    /**
     * Engine event response code
     */
    const ENGINE_EVENT_RESPONSE_OK = 'ok';
    const ENGINE_EVENT_RESPONSE_KO = 'error';

    public function __construct(EventRequest $eventRequest, EventResponse $eventResponse = null)
    {
        $this->request = $eventRequest;
        $this->response = $eventResponse;
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
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Context $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getOriginEventName()
    {
        return $this->originEventName;
    }

    /**
     * @param string $originEventName
     */
    public function setOriginEventName($originEventName)
    {
        $this->originEventName = $originEventName;
    }
}