<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\MapRepository;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;

class MapManager
{
    use ManagerBehavior;

    /**
     * Find a map with its linked objects
     *
     * @param $id
     * @return Map|null
     */
    public function findMap($id)
    {
        return $this
            ->getRepository()
            ->findMap($id)
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