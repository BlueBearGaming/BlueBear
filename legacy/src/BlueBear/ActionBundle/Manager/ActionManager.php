<?php

namespace Sidus\SidusBundle\Action;

use Sidus\SidusBundle\Entity\Object;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ActionManager
 */
class ActionManager
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var \Traversable
     */
    protected static $actions;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Object $object
     *
     * @return array|\Traversable
     */
    public function getActionsForObject(Object $object)
    {
        if ($object->hasPermission(\Sidus\SidusBundle\Permission\PermissionMask::ADMIN)) {
            return $this->getActions();
        }
        $result = [];
        foreach ($this->getActions() as $action) {
            if ($object->hasPermission($action->getPermissionMask())) {
                $result[$action->getName()] = $action;
            }
        }

        return $result;
    }

    /**
     * @return \Traversable
     */
    public function getActions()
    {
        if (self::$actions) {
            return self::$actions;
        }
        $config = $this->container->getParameter('sidus.actions');
        foreach ($config as $key => $value) {
            $class_name          = isset($value['model_class']) ? $value['model_class'] : '\\Sidus\\SidusBundle\\Action\\DefaultAction';
            self::$actions[$key] = new $class_name($value);
        }

        return self::$actions;
    }

    /**
     * @param $name
     *
     * @return null
     */
    public function getAction($name)
    {
        $actions = $this->getActions();
        if (isset($actions[$name])) {
            return $actions[$name];
        }

        return null;
    }
}
