<?php

namespace BlueBear\AdminBundle\Admin;

use BlueBear\BaseBundle\Behavior\StringUtilsTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Exception;

class Admin
{
    use StringUtilsTrait;

    protected $name;

    protected $entityNamespace;

    protected $entities;

    protected $entity;

    protected $repository;

    protected $controller;

    protected $formType;

    protected $currentAction;

    protected $actions = [];

    protected $layout = '';

    protected $blockTemplate;

    public function __construct()
    {
        $this->entities = new ArrayCollection();
    }

    /**
     * Return true if current action is granted for user
     *
     * @param string $actionName Le plus grand de tous les héros
     * @param array $roles
     * @return bool
     */
    public function isActionGranted($actionName, array $roles)
    {
        $isGranted = array_key_exists($actionName, $this->actions);

        // if action exists
        if ($isGranted) {
            $isGranted = false;
            /** @var Action $action */
            $action = $this->actions[$actionName];
            // checking roles permissions
            foreach ($roles as $role) {
                if (in_array($role, $action->getPermissions())) {
                    $isGranted = true;
                }
            }
        }
        return $isGranted;
    }

    /**
     * Generate a route for admin and route name
     *
     * @param $actionName
     * @return string
     */
    public function generateRouteName($actionName)
    {
        return 'bluebear_admin_' . $this->underscore($this->getName()) . '_' . $actionName;
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
     * Return entity path for routing (for example, MyNamespace\EntityName => entityName)
     *
     * @return string
     */
    public function getEntityPath()
    {
        $array = explode('\\', $this->getEntityNamespace());
        $path = array_pop($array);
        $path = strtolower(substr($path, 0, 1)) . substr($path, 1);

        return $path;
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
     * @param $name
     * @return Action
     * @throws Exception
     */
    public function getAction($name)
    {
        if (!array_key_exists($name, $this->getActions())) {
            throw new Exception("Invalid action name \"{$name}\" for admin '{$this->getName()}'");
        }
        return $this->actions[$name];
    }

    /**
     * Return if an action with specified name exists form this admin
     *
     * @param $name
     * @return bool
     */
    public function hasAction($name)
    {
        return array_key_exists($name, $this->actions);
    }

    /**
     * @param array $actions
     */
    public function setActions(array $actions)
    {
        $this->actions = $actions;
    }

    /**
     * @param Action $action
     */
    public function addAction(Action $action)
    {
        $this->actions[$action->getName()] = $action;
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

    /**
     * @return mixed
     */
    public function getEntityNamespace()
    {
        return $this->entityNamespace;
    }

    /**
     * @param mixed $entityNamespace
     */
    public function setEntityNamespace($entityNamespace)
    {
        $this->entityNamespace = $entityNamespace;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param EntityRepository $repository
     */
    public function setRepository(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param mixed $entities
     */
    public function setEntities($entities)
    {
        $this->entities = $entities;
    }

    /**
     * @return mixed
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * @param mixed $formType
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return Action
     */
    public function getCurrentAction()
    {
        return $this->currentAction;
    }

    /**
     * @param Action $currentAction
     */
    public function setCurrentAction(Action $currentAction)
    {
        $this->currentAction = $currentAction;
    }

    /**
     * @return mixed
     */
    public function getBlockTemplate()
    {
        return $this->blockTemplate;
    }

    /**
     * @param mixed $blockTemplate
     */
    public function setBlockTemplate($blockTemplate)
    {
        $this->blockTemplate = $blockTemplate;
    }
}