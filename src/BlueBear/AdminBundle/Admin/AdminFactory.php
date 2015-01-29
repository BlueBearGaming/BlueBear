<?php

namespace BlueBear\AdminBundle\Admin;

use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AdminFactory
{
    protected $admins = [];

    public function __construct(ContainerInterface $container)
    {
        $admins = $container->getParameter('bluebear.admins');
        $mainLayout = $container->getParameter('bluebear.layout');
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.orm.default_entity_manager');

        // creating configured admin
        foreach ($admins as $adminName => $adminConfig) {
            $admin = new Admin();
            $admin->setController($adminConfig['controller']);
            $admin->setEntityNamespace($adminConfig['entity']);
            $admin->setName($adminName);
            $admin->setFormType($adminConfig['form']);
            // admin entity repository
            $admin->setRepository($entityManager->getRepository($admin->getEntityNamespace()));
            // layout is optional
            if ($mainLayout) {
                $admin->setLayout($mainLayout);
            }
            // adding actions
            foreach ($adminConfig['actions'] as $actionName => $actionConfig) {
                // title is optional
                $title = $this->getDefaultActionTitle($admin->getName(), $actionName);

                if (array_key_exists('title', $actionConfig)) {
                    $title = $actionConfig['title'];
                }
                $action = new Action();
                $action->setName($actionName);
                $action->setTitle($title);
                $action->setPermissions($actionConfig['permissions']);
                $action->setRoute($admin->generateRouteName($action->getName()));
                // adding items to actions
                foreach ($actionConfig['fields'] as $fieldName => $fieldConfig) {
                    $field = new Field();
                    $field->setName($fieldName);
                    $field->setTitle($this->inflectString($fieldName));

                    if (array_key_exists('length', $fieldConfig)) {
                        $field->setLength($fieldConfig['length']);
                    }
                    $action->addField($field);
                }
                $admin->addAction($action);
            }
            // adding admins to the pool
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

    protected function getDefaultActionTitle($title, $action)
    {
        $default = $title;

        if ($action == 'list') {
            $default = $this->inflectString($title) . 's list';
        }
        return $default;
    }

    protected function inflectString($string)
    {
        return strtr(ucwords(strtr($string, ['_' => ' ', '.' => '_ ', '\\' => '_ '])), [' ' => '']);
    }
}