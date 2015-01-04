<?php

namespace BlueBear\EngineBundle\Event\Map;

use BlueBear\CoreBundle\Entity\Behavior\HasContainer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
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
        // we set map as response only if required event is loading, not if onMapLoad come from event bubbling
        if ($event->getEventName() == EngineEvent::ENGINE_ON_MAP_LOAD) {
            // return loaded map
            $event->setResponseData($map->toArray());
        }
    }

    /**
     * Save map changes
     *
     * @param EngineEvent $event
     * @throws Exception
     */
    public function onMapSave(EngineEvent $event)
    {
        $this->check($event);
        $data = $event->getData();
        $map = $event->getMap();

        if ($this->propertyExists($data, 'context')) {
            // provided id should be the same as the current map context
            if (!$this->propertyExists($data->context, 'id') or
                $data->context->id != $map->getCurrentContext()->getId()) {
                // invalid context
                throw new Exception('Invalid context id. You should provided the last context id');
            }
            // if mapItems were provided
            if ($this->propertyExists($data->context, 'mapItems')) {
                $mapItems = [];
                
                // update altered mapItems
                foreach ($data->context->mapItems as $mapItemData) {
                    // if a pencil was provided
                    if ($this->propertyExists($mapItemData, 'pencil')) {
                        if (!$this->propertyExists($mapItemData, 'id') and !$this->propertyExists($mapItemData, 'pencil')) {
                            throw new Exception('Invalid pencil data');
                        }
                        // get pencil and layer
                        $pencil = $this->getContainer()->get('bluebear.manager.pencil')->findOneBy([
                            'name' => $mapItemData->pencil->name
                        ]);
                        $layer = $this->getContainer()->get('bluebear.manager.layer')->findOneBy([
                            'name' => $mapItemData->layer->name
                        ]);
                        // test if pencil data are valid
                        if (!$pencil) {
                            throw new Exception('Pencil not found for tileId : ' . $mapItemData->id);
                        }
                        $mapItem = new MapItem();
                        $mapItem->setX($mapItemData->x);
                        $mapItem->setY($mapItemData->y);
                        $mapItem->setPencil($pencil);
                        $mapItem->setLayer($layer);
                        // add to list
                        $mapItems[] = $mapItem;
                    }
                }
                $context = $this->getContextFactory()->update($map, $mapItems);
                $event->setResponseData([
                    'context' => $context->toArray()
                ]);
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

    protected function propertyExists($object, $property)
    {
        return (property_exists($object, $property) and $object->$property);
    }
}