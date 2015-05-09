<?php

namespace BlueBear\GameChessBundle\DataFixtures\ORM;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Map\Layer;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class ChessGameData implements FixtureInterface, ContainerAwareInterface
{
    use ContainerTrait;

    protected $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    protected function createLayers()
    {
//        $layer = new Layer();
//        $layer->setName('');
//        $layer->setLabel($label);
//        $layer->setType($type);
//        $layer->setDescription($description);
//        $layer->setIndex($index);
    }
}