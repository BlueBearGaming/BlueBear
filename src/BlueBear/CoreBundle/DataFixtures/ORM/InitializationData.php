<?php

namespace BlueBear\CoreBundle\DataFixtures\ORM;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class InitializationData implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // create users
        $manager->persist($this->createUser('afrezet', 'admin'));
        $manager->persist($this->createUser('vchalnot', 'admin'));
        $manager->persist($this->createUser('lanzalone', 'admin'));
        // create layers
        $this->createLayers($manager);

        $manager->flush();
    }

    /**
     * Create user (with default email if not provided)
     *
     * @param $userName
     * @param $plainPassword
     * @param null $email
     * @return User
     */
    protected function createUser($userName, $plainPassword, $email = null)
    {
        $user = new User();
        $user->setUsername($userName);
        $user->setPlainPassword($plainPassword);
        $user->setEnabled(true);

        if ($email) {
            $user->setEmail($email);
        } else {
            $user->setEmail($userName . '@clever-age.com');
        }
        return $user;
    }

    /**
     * Create default layers
     *
     * @param ObjectManager $manager
     */
    protected function createLayers(ObjectManager $manager)
    {
        $types = Constant::getLayerTypes();
        $layer = $this->createLayer('layer_0', 'Background', $types['background'], 'Map background', 0);
        $manager->persist($layer);

        $layer = $this->createLayer('layer_1', 'Land', $types['land'], 'Map land (plains, forests...)', 1);
        $manager->persist($layer);

        $layer = $this->createLayer('layer_2', 'Grid', $types['grid'], 'Grid (displayed or not)', 2);
        $manager->persist($layer);

        $layer = $this->createLayer('layer_3', 'Selection', $types['selection'], 'Unit, object or building selection (hover..)', 3);
        $manager->persist($layer);

        $layer = $this->createLayer('layer_4', 'Props and decals', $types['props'], '????', 4);
        $manager->persist($layer);

        $layer = $this->createLayer('layer_5', 'Objects', $types['objects'], 'Objects (item...) on the map', 5);
        $manager->persist($layer);

        $layer = $this->createLayer('layer_6', 'Units', $types['units'], 'Units on the map', 6);
        $manager->persist($layer);

        $layer = $this->createLayer('layer_7', 'Effects', $types['effects'], 'Map Effects', 7);
        $manager->persist($layer);

        $layer = $this->createLayer('layer_8', 'Events', $types['events'], 'Events on map', 8);
        $manager->persist($layer);
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