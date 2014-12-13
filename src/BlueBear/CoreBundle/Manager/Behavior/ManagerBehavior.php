<?php

namespace BlueBear\CoreBundle\Manager\Behavior;

use BlueBear\CoreBundle\Entity\Behavior\HasContainer;
use BlueBear\CoreBundle\Entity\Behavior\HasEntityManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait ManagerBehavior
{
    use HasContainer, HasEntityManager;

    /**
     * Retourne le repository courant
     *
     * @return EntityRepository
     */
    abstract protected function getRepository();

    /**
     * Return a service by its id
     *
     * @param $service
     * @return ContainerInterface
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
     * @return object
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
}