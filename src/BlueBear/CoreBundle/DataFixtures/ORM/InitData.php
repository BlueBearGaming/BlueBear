<?php

namespace BlueBear\CoreBundle\DataFixtures\ORM;

use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InitData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->objectManager = $manager;

        // create layers
        $this->createLayers();
        // create map
        //$this->createMap();
        $this->objectManager->flush();
    }

    /**
     * Create default layers
     */
    protected function createLayers()
    {
        $layer = $this->createLayer('background', 'Background', 'background', 'Map background', 0);
        $this->objectManager->persist($layer);

        $layer = $this->createLayer('land', 'Land', 'land', 'Map land (plains, forests...)', 1);
        $this->objectManager->persist($layer);

        $layer = $this->createLayer('grid', 'Grid', 'grid', 'Grid (displayed or not)', 2);
        $this->objectManager->persist($layer);

        $layer = $this->createLayer('selection', 'Selection', 'selection', 'Unit, object or building selection (hover..)', 3);
        $this->objectManager->persist($layer);

        $layer = $this->createLayer('props', 'Props and decals', 'props', 'Decorations (flowers, broken wall...)', 4);
        $this->objectManager->persist($layer);

        $layer = $this->createLayer('objects', 'Objects', 'objects', 'Objects (item...) on the map', 5);
        $this->objectManager->persist($layer);

        $layer = $this->createLayer('units', 'Units', 'units', 'Units on the map', 6);
        $this->objectManager->persist($layer);

        $layer = $this->createLayer('effects', 'Effects', 'effects', 'Map Effects', 7);
        $this->objectManager->persist($layer);

        $layer = $this->createLayer('events', 'Events', 'events', 'Events on map', 8);
        $this->objectManager->persist($layer);
    }

    protected function createMap()
    {
        // pencil set
        $pencilSet = new PencilSet();
        $pencilSet->setName('bluebear_pencil_set_0');
        $pencilSet->setLabel('Initial pencil set');
        $pencilSet->setType('square');
        $this->objectManager->persist($pencilSet);
        // layers
        $layers = $this
            ->objectManager
            ->getRepository('BlueBearCoreBundle:Map\Layer')
            ->findAll();

        $map = new Map();
        $map->setName('bluebear_map_0');
        $map->setLabel('My first map');
        $map->setType('square');
        $map->setCellSize(128);
        $map->setStartX(0);
        $map->setStartY(0);
        $map->addPencilSet($pencilSet);
        $map->setLayers($layers);
        $this
            ->container
            ->get('bluebear.manager.map')
            ->saveMap($map);
    }

    /**
     * Return new layer
     *
     * @param $name
     * @param $label
     * @param $type
     * @param $description
     * @param $index
     * @return Layer
     */
    protected function createLayer($name, $label, $type, $description, $index)
    {
        $layer = new Layer();
        $layer->setName($name);
        $layer->setLabel($label);
        $layer->setType($type);
        $layer->setDescription($description);
        $layer->setIndex($index);

        return $layer;
    }
}
