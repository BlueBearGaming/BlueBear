<?php

namespace App\Manager\Map;

use BlueBear\BaseBundle\Behavior\ManagerTrait;
use App\Entity\Map\Context;
use App\Entity\Map\Layer;
use App\Entity\Map\MapItem;
use App\Entity\Map\MapItemRepository;
use App\Entity\Map\Pencil;
use App\Utils\Position;

class MapItemManager
{
    use ManagerTrait;

    /**
     * @param Position $position
     * @param Pencil $pencil
     * @param Layer $layer
     * @return MapItem
     */
    public function findByPositionPencilAndLayer(Position $position, Pencil $pencil, Layer $layer)
    {
        return $this->findOneBy([
            'x' => $position->x,
            'y' => $position->y,
            'layer' => $layer->getId(),
            'pencil' => $pencil->getId()
        ]);
    }

    /**
     * @param Context $context
     * @param Position $position
     * @param Layer $layer
     * @return MapItem
     */
    public function findByPositionAndLayer(Context $context, Position $position, Layer $layer)
    {
        return $this->findOneBy([
            'x' => $position->x,
            'y' => $position->y,
            'layer' => $layer->getId(),
            'context' => $context->getId()
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
            ->getRepository('App\Entity\Map\MapItem');
    }
}
