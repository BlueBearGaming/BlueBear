<?php

namespace BlueBear\AdminBundle\Admin;

use BlueBear\AdminBundle\Manager\GenericManager;
use BlueBear\BaseBundle\Behavior\StringUtilsTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Admin
{
    use StringUtilsTrait, ActionTrait;

    /**
     * Admin name
     *
     * @var string
     */
    protected $name;

    /**
     * Full namespace for Admin entity
     *
     * @var string
     */
    protected $entityNamespace;

    /**
     * Entities collection
     *
     * @var ArrayCollection
     */
    protected $entities;

    /**
     * Entity
     *
     * @var Object
     */
    protected $entity;

    /**
     * Entity manager (doctrine entity manager by default)
     *
     * @var GenericManager
     */
    protected $manager;

    /**
     * Actions called when using custom manager
     *
     * @var array
     */
    protected $customManagerActions;

    /**
     * Entity repository
     *
     * @var EntityRepository
     */
    protected $repository;

    /**
     * Controller
     *
     * @var Controller
     */
    protected $controller;

    /**
     * Form type
     *
     * @var string
     */
    protected $formType;

    /**
     * Templates layout
     *
     * @var string
     */
    protected $layout = '';

    protected $blockTemplate;

    public function __construct($name, $repository, $manager, $controller, $entityNamespace, $formType, $blockTemplate, $layout = '')
    {
        $this->name = $name;
        $this->repository = $repository;
        $this->manager = $manager;
        $this->controller = $controller;
        $this->entityNamespace = $entityNamespace;
        $this->formType = $formType;
        $this->blockTemplate = $blockTemplate;
        $this->layout = $layout;
        $this->entities = new ArrayCollection();
        $this->customManagerActions = [];
    }

    /**
     * Generate a route for admin and action name
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
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @return mixed
     */
    public function getEntityNamespace()
    {
        return $this->entityNamespace;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
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
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getEntity()
    {
        if (!$this->entity) {
            throw new Exception("Entity not found in admin \"{$this->getName()}\". Try call method findEntity or createEntity first.");
        }
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function findEntity($field, $value)
    {
        $this->entity = $this->getManager()->findOneBy([
            $field => $value
        ]);
        $this->checkEntity();
        return $this->entity;
    }

    public function saveEntity()
    {
        $this->checkEntity();
        $this->getManager()->save($this->entity);
    }

    public function createEntity()
    {
        $this->entity = $this->getManager()->create($this->getEntityNamespace());
        $this->checkEntity();

        return $this->entity;
    }

    public function deleteEntity()
    {
        $this->checkEntity();
        $this->getManager()->delete($this->entity);
    }

    /**
     * @return mixed
     */
    public function getBlockTemplate()
    {
        return $this->blockTemplate;
    }

    /**
     * @return GenericManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    protected function checkEntity()
    {
        if (!$this->entity) {
            throw new Exception("Entity not found in admin \"{$this->getName()}\". Try call method findEntity or createEntity first.");
        }
    }
}