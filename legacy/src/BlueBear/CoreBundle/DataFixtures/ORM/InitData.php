<?php

namespace App\DataFixtures\ORM;

use App\Entity\Map\Layer;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

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
        $this->objectManager->flush();
    }

    /**
     * Create default layers
     */
    protected function createLayers()
    {
        $layersData = Yaml::parse(__DIR__.'/../../Resources/fixtures/layers.yml');
        $index = 0;
        foreach ($layersData as $layerData) {
            $layer = new Layer();
            $layer->setName($layerData['name']);
            $layer->setLabel($layerData['label']);
            $layer->setType($layerData['type']);
            $layer->setDescription($layerData['description']);
            $layer->setIndex($index);

            $this->objectManager->persist($layer);
            $index++;
        }
    }
}
