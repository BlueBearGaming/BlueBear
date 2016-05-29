<?php

namespace BlueBear\FireBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

/**
 * Load yml fixtures from alice bundle
 */
class FixturesLoader implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        Fixtures::load([
            __DIR__ . '/Layers.yml',
            __DIR__ . '/Maps.yml',
            __DIR__ . '/Contexts.yml',
            __DIR__ . '/MapItems.yml',
            __DIR__ . '/Users.yml',
            __DIR__ . '/Player.yml',
        ], $manager, [
            'locale' => 'fr_FR'
        ]);
    }
}
