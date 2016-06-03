<?php

namespace LAG\AdminEAVBridgeBundle\Repository;

use LAG\AdminBundle\Repository\RepositoryInterface;
use Sidus\EAVModelBundle\Entity\DataRepository;

class DoctrineEAVRepository extends DataRepository implements RepositoryInterface
{
    /**
     * Save an entity
     *
     * @param $entity
     */
    public function save($entity)
    {
        $this
            ->_em
            ->persist($entity);
        $this
            ->_em
            ->flush();
    }

    /**
     * Delete an entity
     *
     * @param object $entity
     */
    public function delete($entity)
    {
        $this
            ->_em
            ->remove($entity);
        $this
            ->_em
            ->flush();
    }
}
