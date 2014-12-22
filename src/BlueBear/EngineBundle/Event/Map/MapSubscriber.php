<?php

namespace BlueBear\EngineBundle\Event\Map;

use BlueBear\CoreBundle\Entity\Behavior\HasContainer;
use BlueBear\EngineBundle\Behavior\HasContextFactory;
use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapSubscriber implements EventSubscriberInterface
{
    use HasContextFactory, HasContainer;

    /**
     * Subscribe on mapLoad, mapSave events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_MAP_LOAD => 'onMapLoad',
            EngineEvent::ENGINE_ON_MAP_SAVE => 'onMapSave'
        ];
    }

    /**
     * Load map event :
     *   - load map
     *   - load execution context, create it if required
     *
     * @param EngineEvent $event
     * @throws Exception
     */
    public function onMapLoad(EngineEvent $event)
    {
        $map = $event->getMap();
        $data = $event->getData();

        // if a context id is provided, we try to load this context
        if (property_exists($data, 'contextId') and $data->contextId) {
            $this->getContextFactory()->loadContext($map, $data->contextId);
        }
        // if no context id, the last context should be loaded. If not we create the initial context
        else if (!$map->getCurrentContext()) {
            $this->getContextFactory()->create($map);
        }
        // return loaded map
        $event->setResponseData($map->toArray());
    }

    public function onMapSave(EngineEvent $event)
    {
        $this->check($event);
        $data = $event->getData();
        $map = $event->getMap();

        if (property_exists($data, 'context')) {
            // provided id should be the same as the current map context
            if (!property_exists($data->context, 'id') or
                $data->context->id != $map->getCurrentContext()->getId()) {
                // invalid context
                throw new Exception('Invalid context id. You should provided the last context id');
            }
            // if tiles were provided
            if (property_exists($data->context, 'tiles') and is_array($data->context->tiles)) {
                $tiles = $map->getCurrentContext()->getTilesById();
                $ids = array_keys($tiles);

                // update altered tiles
                foreach ($data->context->tiles as $tileData) {
                    // tiles should exists
                    if (!property_exists($tileData, 'id') or !array_key_exists($tileData->id, $ids)) {
                        throw new Exception('Invalid tile id');
                    }
                    // if a pencil was provided
                    if (property_exists($tileData, 'pencil')) {

                    }

                }
            }

        }
    }

    /**
     * Check if the map is properly loaded
     *
     * @param EngineEvent $event
     * @throws Exception
     */
    protected function check(EngineEvent $event)
    {
        $map = $event->getMap();

        // map should be valid
        if (!$map) {
            throw new Exception('Map should be loaded in map events');
        }
        // map should have a context
        if (!$map->getCurrentContext()) {
            throw new Exception('Map should have a execution context');
        }
    }
}