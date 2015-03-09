<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Editor\ItemRepository;
use BlueBear\BaseBundle\Behavior\ManagerTrait;

class ItemManager
{
    use ManagerTrait;

    /**
     * Return uncompleted items
     *
     * @return array
     */
    public function findUncompleted()
    {
        return $this->getRepository()
            ->findUncompleted()
            ->getQuery()
            ->getResult();
    }

    /**
     * Return items repository
     *
     * @return ItemRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBear\CoreBundle\Entity\Editor\Item');
    }
}