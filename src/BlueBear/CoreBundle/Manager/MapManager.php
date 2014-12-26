<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\MapRepository;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;
use Doctrine\ORM\NonUniqueResultException;

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
     * Save map
     *
     * @param Map $map
     */
    public function saveMap(Map $map)
    {
        $this->save($map);
    }

    /**
     * Find the first map
     *
     * @return Map
     * @throws NonUniqueResultException
     */
    public function findOne()
    {
        return $this
            ->getRepository()
            ->findOne()
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Return number of maps
     *
     * @return mixed
     */
    public function count()
    {
        return $this
            ->getRepository()
            ->count();
    }

    /**
     * Return map repository
     *
     * @return MapRepository
     */
    protected function getRepository()
    {
        return $this
            ->getEntityManager()
            ->getRepository('BlueBear\CoreBundle\Entity\Map\Map');
    }

    public function __call($name, $arguments)
    {
        $repo = $this->getRepository();
        return call_user_method_array($name, $repo, $arguments);
    }
}