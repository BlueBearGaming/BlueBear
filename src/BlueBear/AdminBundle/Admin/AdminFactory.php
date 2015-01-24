<?php

namespace BlueBear\AdminBundle\Admin;

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AdminFactory
{
    protected $admins = [];

    public function __construct(ContainerInterface $container)
    {
        $admins = $container->getParameter('bluebear.admins');
        // creating a route by admin and action
        foreach ($admins as $adminName => $adminConfig) {
            $admin = new Admin();
            $admin->setController($adminConfig['controller']);
            $admin->setEntity($adminConfig['entity']);
            $admin->setName($adminName);
            $admin->setActions($adminConfig['actions']);
            $this->admins[] = $admin;
        }
    }

    /**
     * @param $name
     * @return Admin
     * @throws Exception
     */
    public function getAdmin($name)
    {
        if (!array_key_exists($name, array_keys($this->admins))) {
            throw new Exception('Invalid admin name. Did you add it in config.yml ?');
        }
        return $this->admins[$name];
    }

    /**
     * @return Admin[]
     */
    public function getAdmins()
    {
        return $this->admins;
    }
}