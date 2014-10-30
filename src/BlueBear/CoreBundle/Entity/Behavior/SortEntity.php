<?php

namespace BlueBear\CoreBundle\Entity\Behavior;

trait SortEntity
{
    protected function getSortedEntityForChoice($entities)
    {
        $sorted = [];

        foreach ($entities as $entity) {
            $sorted[$entity->getId()] = $entity->getLabel() . ' ("' . $entity->getName() . '")';
        }
        return $sorted;
    }
} 