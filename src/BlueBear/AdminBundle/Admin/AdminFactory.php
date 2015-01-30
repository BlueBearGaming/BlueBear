<?php

namespace BlueBear\AdminBundle\Admin;

use BlueBear\BaseBundle\Behavior\StringUtilsTrait;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * AdminFactory
 *
 * Create admin from configuration
 */
class AdminFactory
{
    use StringUtilsTrait;

    protected $admins = [];

    /**
     * Read configuration from container, then create admin with its actions and fields
     *
     * @param ContainerInterface $container
     */
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
            $admin->setBlockTemplate($container->getParameter('bluebear.blocks_template'));
            // admin entity repository
            $admin->setRepository($entityManager->getRepository($admin->getEntityNamespace()));
            // layout is optional
            if ($mainLayout) {
                $admin->setLayout($mainLayout);
            }
            if (!array_key_exists('actions', $adminConfig) or !$adminConfig['actions']) {
                $adminConfig['actions'] = $this->getDefaultActions();
            }
            // adding actions
            foreach ($adminConfig['actions'] as $actionName => $actionConfig) {
                // test each key to keep granularity in configuration
                if (array_key_exists('title', $actionConfig)) {
                    $title = $actionConfig['title'];
                } else {
                    // default title
                    $title = $this->getDefaultActionTitle($admin->getName(), $actionName);
                }
                if (array_key_exists('permissions', $actionConfig)) {
                    $permissions = $actionConfig['permissions'];
                } else {
                    $permissions = $this->getDefaultPermissions();
                }
                if (array_key_exists('fields', $actionConfig)) {
                    $fields = $actionConfig['fields'];
                } else {
                    $fields = $this->getDefaultFields();
                }
                $action = new Action();
                $action->setName($actionName);
                $action->setTitle($title);
                $action->setPermissions($permissions);
                $action->setRoute($admin->generateRouteName($action->getName()));
                // adding items to actions
                foreach ($fields as $fieldName => $fieldConfig) {
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
     * Return an loaded admin from a Symfony request
     *
     * @param Request $request
     * @return Admin
     * @throws Exception
     */
    public function getAdminFromRequest(Request $request)
    {
        $requestParameters = explode('/', $request->getPathInfo());
        // remove empty string
        array_shift($requestParameters);
        // get configured admin
        return $this->getAdmin($this->underscore($requestParameters[0]));
    }

    /**
     * Return a admin by its name
     *
     * @param $name
     * @return Admin
     * @throws Exception
     */
    public function getAdmin($name)
    {
        if (!array_key_exists($name, $this->admins)) {
            throw new Exception('Invalid admin name "' . $name . '". Did you add it in config.yml ?');
        }
        return $this->admins[$name];
    }

    /**
     * Return all admins
     *
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

    protected function getDefaultActions()
    {
        return [
            'list' => [],
            'create' => [],
            'edit' => [],
            'delete' => []
        ];
    }

    protected function getDefaultPermissions()
    {
        return [
            'ROLE_USER'
        ];
    }

    protected function getDefaultFields()
    {
        return [
            'id' => [],
            'label' => []
        ];
    }
}