<?php

namespace BlueBear\CoreBundle\Entity\Game;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class GameActionRepository extends EntityRepository
{
    /**
     * Return the first action of the stack for a game
     *
     * @param $gameId
     * @return QueryBuilder
     */
    public function findFirst($gameId)
    {
        return $this
            ->createQueryBuilder('action')
            ->where('action.game = :game')
            ->setParameter('game', $gameId)
            ->setMaxResults(1);
    }
}
