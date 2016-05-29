<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerTrait;
use BlueBear\CoreBundle\Entity\Map\Army;
use BlueBear\CoreBundle\Entity\Game\Player\Player;
use Doctrine\ORM\EntityRepository;

class ArmyManager
{
    use ManagerTrait;

    public function reset(Army $army)
    {
        $army->setEntityInstances(null);
        $this->save($army);
    }

    public function create($label, Player $player)
    {
        $army = new Army();
        $army->setLabel($label);
        $army->setName($label);
        $army->setPlayer($player);
        $this->save($army);

        return $army;
    }

    /**
     * Return current manager repository
     *
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this
            ->getEntityManager()
            ->getRepository('BlueBearCoreBundle:Map\Army');
    }
}
