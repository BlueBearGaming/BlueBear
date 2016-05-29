<?php

namespace BlueBear\BaseBundle\Behavior;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;

trait ManagerTrait
{
    use ContainerTrait, EntityManagerTrait;

    /**
     * Return current manager repository
     *
     * @return EntityRepository
     */
    abstract protected function getRepository();

    /**
     * Return a service by its id
     *
     * @param $service
     * @return object
     */
    public function get($service)
    {
        return $this->getContainer()->get($service);
    }

    /**
     * Save and optionally flush an entity
     *
     * @param $entity
     * @param bool $flush
     * @return $this
     */
    public function save($entity, $flush = true)
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush($entity);
        }
        return $this;
    }

    /**
     * Remove and optionally flush an entity
     *
     * @param $entity
     * @param bool $flush
     * @return $this
     */
    public function delete($entity, $flush = true)
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return $this;
    }

    /**
     * Find an entity by its id
     *
     * @param $id
     * @return object
     */
    public function find($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * Find all entities in current repository
     *
     * @return array
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Flush current entity manager
     *
     * @param null $entity
     */
    public function flush($entity = null)
    {
        $this->getEntityManager()->flush($entity);
    }

    /**
     * Return an entity by its property
     *
     * @param $arguments
     * @return null|object
     */
    public function findOneBy(array $arguments)
    {
        return $this
            ->getRepository()
            ->findOneBy($arguments);
    }

    /**
     * Return entities by criteria
     *
     * @param $arguments
     * @return array
     */
    public function findBy(array $arguments)
    {
        return $this
            ->getRepository()
            ->findBy($arguments);
    }

    /**
     * Return current entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this
            ->container
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @param $method
     * @param $arguments
     * @return array|object
     * @throws ORMException
     */
    public function __call($method, $arguments)
    {
        return $this->getRepository()->__call($method, $arguments);
    }
}
