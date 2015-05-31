<?php

namespace BlueBear\CoreBundle\Manager;


use BlueBear\BaseBundle\Behavior\ManagerTrait;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Game;
use BlueBear\CoreBundle\Entity\Map\MapItem;

class GameManager
{
    use ManagerTrait;

    public function create(Context $initialContext)
    {
        $game = new Game();
        $context = new Context();
        $mapItems = $initialContext->getMapItems();

        foreach ($mapItems as $mapItem) {
            $clonedMapItem = new MapItem();
            $clonedMapItem->setPencil($mapItem->getPencil());
            $clonedMapItem->setLayer($mapItem->getLayer());
            $clonedMapItem->setListeners($mapItem->getListeners());
            $clonedMapItem->setPosition($mapItem->getPosition());
            $clonedMapItem->setContext($context);
        }




    }

    public function getRepository()
    {
        return $this
            ->getEntityManager()
            ->getRepository('BlueBearCoreBundle:Map\Game');
    }
}
