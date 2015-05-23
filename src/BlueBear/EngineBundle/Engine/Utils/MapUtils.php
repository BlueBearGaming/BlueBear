<?php

namespace BlueBear\EngineBundle\Engine\Utils;


use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest;

/**
 * @deprecated
 */
class MapUtils
{
    public function getMapItemForSelection(Position $sourcePosition, $sourceLayerType, Position $selectionPosition)
    {
        $emptyPencil = new Pencil();
        $emptyPencil->setName(Constant::PENCIL_TYPE_EMPTY);
        $eventLayer = new Layer();
        $eventLayer->setName(Constant::LAYER_TYPE_EVENTS);
        // selection layer
        $selectionLayer = new Layer();
        $selectionLayer->setName(Constant::LAYER_TYPE_SELECTION);
        // selection pencil should be handled client side
        $selectionPencil = new Pencil();
        $selectionPencil->setName(Constant::PENCIL_TYPE_SELECTION);
        // source target for next click event should be current map item
        $clickListener['source']['position'] = $sourcePosition;
        $clickListener['source']['layer'] = $sourceLayerType;
        // map item for selection layer
        $mapItemForSelection = new MapItem();
        $mapItemForSelection->setLayer($selectionLayer);
        $mapItemForSelection->setPencil($selectionPencil);
        $mapItemForSelection->setX($selectionPosition->x);
        $mapItemForSelection->setY($selectionPosition->y);
        // map item for event layer
        $mapItemForEvent = new MapItem();
        $mapItemForEvent->setX($selectionPosition->x);
        $mapItemForEvent->setY($selectionPosition->y);
        $mapItemForEvent->setListeners([
            'click' => $clickListener
        ]);
        $mapItemForEvent->setLayer($eventLayer);
        $mapItemForEvent->setPencil($emptyPencil);

        return [
            $mapItemForSelection,
            $mapItemForEvent
        ];
    }

    public function getSelectionForTarget(MapItemSubRequest $target)
    {
        return $this->getMapItemForSelection($target->position, $target->layer, $target->position);
    }
}
