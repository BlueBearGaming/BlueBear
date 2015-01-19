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

    protected function createLayers(ObjectManager $manager)
    {
        $types = Constant::getLayerTypes();

        $layer = new Layer();
        $layer->setName('layer_0');
        $layer->setLabel('Background');
        $layer->setType($types['background']);
        $layer->setDescription('Maps background');
        $layer->setIndex(0);

        /*
         * 'background' => 'Background',
            'land' => 'Land',
            'grid' => 'Grid',
            'selection' => 'Selection',
            'buildings' => 'Buildings',
            'props' => 'Props and Decals',
            'objects' => 'Objects',
            'units' => 'Units',
            'effects' => 'Effects',
            'events' => 'Events',
         */
        //
    }
}