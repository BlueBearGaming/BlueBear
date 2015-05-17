<?php

namespace BlueBear\UserBundle\DataFixtures\ORM;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class InitData implements FixtureInterface, ContainerAwareInterface
{
    use ContainerTrait;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->objectManager = $manager;
        // create users
        $this->createUser('afrezet', 'admin');
        $this->createUser('vchalnot', 'admin');
        $this->createUser('lanzalone', 'admin');
        $this->createUser('lutangar', 'admin');
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
        $user->setRoles([
            'ROLE_USER',
            'ROLE_ADMIN',
        ]);

        if ($email) {
            $user->setEmail($email);
        } else {
            $user->setEmail($userName . '@clever-age.com');
        }
        $this->objectManager->persist($user);
        $this->objectManager->flush($user);
    }
}