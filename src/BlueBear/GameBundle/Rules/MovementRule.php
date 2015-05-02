<?php

namespace BlueBear\GameBundle\Rules;


use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\EngineBundle\Rules\Rule;

class MovementRule implements Rule
{
    use ContainerTrait;

    /**
     * @return callable
     */
    public function getCallback()
    {
        return function (MapItem $mapItem) {
            //$this->getContainer()->get('bluebear.manager.entity_instance')->findByTypeAndPosition();
            // return only land map items
            return $mapItem->getLayer()->getType() == Constant::LAYER_TYPE_LAND;
        };
    }
}