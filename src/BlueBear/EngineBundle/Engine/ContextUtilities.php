<?php

namespace BlueBear\EngineBundle\Engine;


use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest;

class ContextUtilities {

    /** @var  Context */
    protected $context;

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Context $context
     * @return ContextUtilities $this
     */
    public function setContext(Context $context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @param int $x
     * @param int $y
     * @param string $pencilName
     * @param string $layerName
     * @return MapItem
     */
    public function createSelectionMapItem($x, $y, $pencilName = Constant::PENCIL_TYPE_SELECTION, $layerName = Constant::LAYER_TYPE_SELECTION)
    {
        $mapItem = new MapItem();
        $mapItem->setLayer($this->getLayerByNameOrCreate($layerName));
        $mapItem->setPencil($this->getPencilByNameOrCreate($pencilName));
        $mapItem->setX($x);
        $mapItem->setY($y);
        return $mapItem;
    }

    /**
     * @param int $x
     * @param int $y
     * @param array $listeners
     * @param string $layerName
     * @return MapItem
     */
    public function createEventMapItem($x, $y, array $listeners, $layerName = Constant::LAYER_TYPE_EVENTS)
    {
        $emptyPencil = new Pencil();
        $emptyPencil->setName(Constant::PENCIL_TYPE_EMPTY);
        $mapItem = new MapItem();
        $mapItem->setLayer($this->getLayerByNameOrCreate($layerName));
        $mapItem->setPencil($emptyPencil);
        $mapItem->setX($x);
        $mapItem->setY($y);
        $mapItem->setListeners($listeners);
        return $mapItem;
    }

    /**
     * @param string $pencilName
     * @return Pencil
     */
    public function getPencilByNameOrCreate($pencilName)
    {
        $pencil = $this->context->getMap()->getPencilByName($pencilName);
        if (!$pencil) {
            $pencil = new Pencil();
            $pencil->setName($pencilName);
        }
        return $pencil;
    }

    /**
     * @param $layerName
     * @return Layer
     */
    public function getLayerByNameOrCreate($layerName)
    {
        $layer = $this->context->getMap()->getLayerByName($layerName);
        if (!$layer) {
            $layer = new Layer();
            $layer->setName($layerName);
        }
        return $layer;
    }

    public function selectTarget(MapItemSubRequest $target)
    {
        return $this->createSelectionMapItem($target->position->x, $target->position->y);
    }
}
