<?php

namespace BlueBear\CoreBundle\Entity\Game;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class GameRepository extends EntityRepository
{
    /**
     * Return running games query builder
     *
     * @return QueryBuilder
     */
    public function findInProgress()
    {
        return $this
            ->createQueryBuilder('game')
            ->innerJoin('game.actionStack', 'stack');
    }
}
