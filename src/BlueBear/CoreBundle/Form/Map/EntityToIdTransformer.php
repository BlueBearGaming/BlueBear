<?php

namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\BaseBundle\Behavior\ManagerBehavior;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * EntityToChoiceTransformer
 *
 * Transforms a list of entities into an id
 */
class EntityToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ManagerBehavior $manager
     */
    protected $manager;

    protected $map;

    /**
     * @param mixed $entity
     * @return mixed
     */
    public function transform($entity)
    {
        /** @var Id $entity */
        if (!$entity) {
            return '';
        }
        return $entity->getId();
    }

    public function reverseTransform($value)
    {
        $entity = null;

        if ($value) {
            $entity = $this->manager->find($value);
        }
        return $entity;
    }

    public function setManager($manager)
    {
        $this->manager = $manager;
    }
}