<?php

namespace BlueBear\BaseBundle\Behavior;

use Doctrine\ORM\EntityManagerInterface;

trait EntityManagerTrait
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
