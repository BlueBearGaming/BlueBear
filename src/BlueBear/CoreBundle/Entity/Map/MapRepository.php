<?php

namespace BlueBear\CoreBundle\Entity\Map;

use Doctrine\ORM\EntityRepository;

class MapRepository extends EntityRepository
{
    public function find($id)
    {
        return $this->createQueryBuilder('map')
            ->join('map.pencilSets', 'pencilSets');
    }
}