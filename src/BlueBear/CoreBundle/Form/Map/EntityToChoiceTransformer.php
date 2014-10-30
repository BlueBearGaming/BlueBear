<?php

namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * EntityToChoiceTransformer
 *
 * Transforms a list of entities into a list of id
 */
class EntityToChoiceTransformer implements DataTransformerInterface
{
    /**
     * @var ManagerBehavior $manager
     */
    protected $manager;

    protected $map;

    public function transform($entities)
    {
        $sorted = [];

        if (!$entities) {
            $entities = [];
        }
        /** @var Id $entity */
        foreach ($entities as $entity) {
            $sorted[] = $entity->getId();
        }
        return $sorted;
    }

    public function reverseTransform($value)
    {
        $entities = [];

        if (!is_array($value)) {
            throw new TransformationFailedException('Invalid data, expected array of integer');
        }
        foreach ($value as $id) {
            $entity = $this->manager->find($id);

            if (!$entity) {
                throw new TransformationFailedException('Entity not found (id: ' . $id . ')');
            }
            $entities[] = $entity;
        }
        return $entities;
    }

    public function setManager($manager)
    {
        $this->manager = $manager;
    }
}