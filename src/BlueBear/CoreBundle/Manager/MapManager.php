<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerBehavior;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\MapRepository;
use BlueBear\CoreBundle\Entity\Map\UserContext;
use BlueBear\UserBundle\Entity\User;
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
     * @param User $user
     */
    public function saveMap(Map $map)
    {
        $user = $this->getContainer()->get('security.context')->getToken()->getUser();
        $isNew = !($map->getId());
        $this->save($map);

        // if map is new, we create a initial context
        if ($isNew) {
            $context = new Context();
            $context->setLabel('Initial context');
            $context->setVersion(0);
            $context->setMap($map);

            $userContext = new UserContext();
            $userContext->setUser($user);
            $userContext->setContext($context);

            $this->save($context);
            $this->save($userContext);
            $this->save($map);
        }
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
}