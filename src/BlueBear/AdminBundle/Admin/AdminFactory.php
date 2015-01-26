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
        $mainLayout = $container->getParameter('bluebear.layout');
        // creating configured admin
        foreach ($admins as $adminName => $adminConfig) {
            $admin = new Admin();
            $admin->setController($adminConfig['controller']);
            $admin->setEntity($adminConfig['entity']);
            $admin->setName($adminName);
            $admin->setActions($adminConfig['actions']);

            // layout is optional
            if ($mainLayout) {
                $admin->setLayout($mainLayout);
            }
            $this->admins[$admin->getName()] = $admin;
        }
    }

    /**
     * @param $name
     * @return Admin
     * @throws Exception
     */
    public function getAdmin($name)
    {
        if (!array_key_exists($name, $this->admins)) {
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