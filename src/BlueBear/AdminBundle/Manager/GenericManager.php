<?php

namespace BlueBear\AdminBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class GenericManager
{

    protected $customManager;

    protected $entityRepository;

    /**
     * Doctrine entity manager
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected $methodsMapping;

    public function __construct(EntityRepository $entityRepository, EntityManager $entityManager, $customManager = null, $methodsMapping = [])
    {
        $this->entityRepository = $entityRepository;
        $this->customManager = $customManager;
        $this->entityManager = $entityManager;
        $this->methodsMapping = $methodsMapping;
    }

    public function findOneBy($arguments = [])
    {
        if ($this->methodMatch('findOneBy')) {
            $method = $this->methodsMapping['findOneBy'];
            $entity = $this->customManager->$method($arguments);
        } else {
            $entity = $this->entityRepository->findOneBy($arguments);
        }
        return $entity;
    }

    public function findAll()
    {
        return [];
    }

    public function save($entity, $flush = true)
    {
        if ($this->methodMatch('save')) {
            $method = $this->methodsMapping['save'];
            $this->customManager->$method($entity, $flush);
        } else {
            $this->entityManager->persist($entity);

            if ($flush) {
                $this->entityManager->flush($entity);
            }
        }
    }

    public function create($entityNamespace)
    {
        $entity = new $entityNamespace;

        if ($this->methodMatch('create')) {
            $method = $this->methodsMapping['create'];
            $this->customManager->$method($entityNamespace);
        }
        return $entity;
    }

    public function delete($entity, $flush = true)
    {
        if ($this->methodMatch('delete')) {
            $method = $this->methodsMapping['delete'];
            $this->customManager->$method($entity);
        } else {
            $this->entityManager->remove($entity);

            if ($flush) {
                $this->entityManager->flush($entity);
            }
        }
    }

    protected function methodMatch($method)
    {
        return array_key_exists($method, $this->methodsMapping);
    }
}