<?php

namespace BlueBear\EngineBundle\Event;

use BlueBear\CoreBundle\Entity\Map\Context;
use Symfony\Component\EventDispatcher\Event;

class EngineEvent extends Event
{
    /**
     * Engine events (private)
     */
    const ENGINE_ON_ENGINE_EVENT = 'bluebear.engine.engineEvent';
    const ENGINE_MAP_SAVE = 'bluebear.engine.onMapSave';

    /**
     * Game
     */
    const ENGINE_GAME_CREATE = 'bluebear.engine.gameCreate';
    const HELL_ARENA_GAME_CREATE = 'bluebear.arena.gameCreate';
    const ENGINE_GAME_COMBAT_INIT = 'bluebear.combat.init';
    const ENGINE_GAME_COMBAT_ATTACK = 'bluebear.combat.attack';
    const ENGINE_GAME_TURN = 'bluebear.game.turn';
    const ENGINE_GAME_END_OF_TURN = 'bluebear.game.endOfTurn';

    /**
     * Map events
     */
    const ENGINE_MAP_LOAD = 'bluebear.engine.mapLoad';

    /**
     * MapItem events
     */
    const ENGINE_MAP_ITEM_CLICK = 'bluebear.engine.mapItemClick';
    const ENGINE_MAP_ITEM_MOVE = 'bluebear.engine.mapItemMove';
    const ENGINE_CLIENT_UPDATE = 'bluebear.engine.clientUpdate';

    const EDITOR_MAP_PUT_PENCIL = 'bluebear.editor.putPencil';
    const EDITOR_MAP_PUT_ENTITY = 'bluebear.editor.putEntity';
    const EDITOR_MAP_UPDATE = 'bluebear.editor.mapUpdate';

    /**
     * Engine event response code
     */
    const ENGINE_EVENT_RESPONSE_OK = 'ok';
    const ENGINE_EVENT_RESPONSE_KO = 'error';

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
     * @var bool
     */
    protected $requestClientUpdate = false;

    /**
     * EngineEvent constructor.
     *
     * @param EventRequest|null $eventRequest
     * @param EventResponse|null $eventResponse
     */
    public function __construct(EventRequest $eventRequest = null, EventResponse $eventResponse = null)
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
     * @return bool
     */
    public function getRequestClientUpdate()
    {
        return $this->requestClientUpdate;
    }

    /**
     * @param bool $requestClientUpdate
     */
    public function setRequestClientUpdate($requestClientUpdate)
    {
        $this->requestClientUpdate = $requestClientUpdate;
    }
}
