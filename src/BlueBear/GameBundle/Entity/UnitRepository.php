<?php

namespace BlueBear\GameBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UnitRepository extends EntityRepository
{
    public function findByPosition($contextId, $x, $y)
    {
        return $this
            ->createQueryBuilder('unit')
            ->join('unit.', '')
            ->where('unit');
    }
}