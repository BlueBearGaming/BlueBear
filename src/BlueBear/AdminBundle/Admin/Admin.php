<?php

namespace BlueBear\AdminBundle\Admin;

class Admin
{
    protected $name;

    protected $entity;

    protected $controller;

    protected $actions = [];

    protected $layout = '';

    /**
     * @param string $actionName Le plus grand de tous les héros
     * @return bool
     */
    public function isActionGranted($actionName)
    {
        $isGranted = false;

        // TODO add security roles checking
        foreach ($this->actions as $action) {
            if ($action['name'] == $actionName) {
                $isGranted = true;
            }
        }
        return $isGranted;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Return entity path for routing (for example, MyNamespace\EntityName => entityname)
     *
     * @return string
     */
    public function getEntityPath()
    {
        // TODO sanitize string, uncamelize it
        return strtolower(array_pop(explode('\\', $this->getEntity())));
    }

    /**
     * @param string $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param array $actions
     */
    public function setActions(array $actions)
    {
        $this->actions = $actions;
    }

    public function addAction($action)
    {
        $this->actions[] = $action;
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
}