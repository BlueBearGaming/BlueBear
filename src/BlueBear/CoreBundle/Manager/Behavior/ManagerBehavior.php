<?php

namespace BlueBear\CoreBundle\Manager\Behavior;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

// TODO translate comments
trait ManagerBehavior
{
    /**
     * Current manager $entityManager
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    /**
     * Le conteneur de services
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * Retourne le repository courant
     *
     * @return EntityRepository
     */
    abstract protected function getRepository();

    /**
     * Définis le conteneur de services
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Retourne le conteneur de services
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Retourne un service
     *
     * @param $service
     * @return ContainerInterface
     */
    public function get($service)
    {
        return $this->getContainer()->get($service);
    }

    /**
     * Sauvegarde d'une entité
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
     * Suppression d'une entité
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
     * Retourne une entité par son identifiant
     *
     * @param $id
     * @return object
     */
    public function find($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * Retourne une entité par son identifiant
     *
     * @return object
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    // TODO vérifier l'utilité de la fonction par rapport à la fonction save()
    public function flush($entity = null)
    {
        $this->getEntityManager()->flush($entity);
    }

    /**
     * Retourne le manager d'entités
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (!$this->entityManager) {
            $this->entityManager = $this->container->get('doctrine')->getManager();
        }
        return $this->entityManager;
    }
}