<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\LayerRepository;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\MapRepository;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;

class MapManager
{
    use ManagerBehavior;

    /**
     * @param $id
     * @return Map|null
     */
    public function find($id)
    {
        return $this
            ->getRepository()
            ->find($id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Return layers repository
     *
     * @return MapRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBear\CoreBundle\Entity\Map\Map');
    }
}