<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\MapItemRepository;
use BlueBear\BaseBundle\Behavior\ManagerBehavior;
use BlueBear\CoreBundle\Utils\Position;

class MapItemManager
{
    use ManagerBehavior;

    /**
     * @param Position $position
     * @param Layer $layer
     * @return MapItem
     */
    public function findByPositionAndLayer(Position $position, Layer $layer)
    {
        return $this->findOneBy([
            'x' => $position->getX(),
            'y' => $position->getY(),
            'layer' => $layer->getId()
        ]);
    }

    /**
     * Return mapItem repository
     *
     * @return MapItemRepository
     */
    protected function getRepository()
    {
        return $this
            ->getEntityManager()
            ->getRepository('BlueBear\CoreBundle\Entity\Map\MapItem');
    }
}