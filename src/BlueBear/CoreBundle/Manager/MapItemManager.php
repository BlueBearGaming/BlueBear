<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItemRepository;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;

class MapItemManager
{
    use ManagerBehavior;

    public function findByPositionAndLayer($x, $y, Layer $layer)
    {
        return $this->findOneBy([
            'x' => $x,
            'y' => $y,
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