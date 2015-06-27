<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerTrait;
use BlueBear\CoreBundle\Entity\Game\GameAction;
use BlueBear\CoreBundle\Entity\Game\GameActionRepository;

class GameActionManager
{
    use ManagerTrait;

    /**
     * Return the first action of the stack for a game
     *
     * @param $gameId
     * @return GameAction
     */
    public function findFirst($gameId)
    {
        return $this
            ->getRepository()
            ->findFirst($gameId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function removeFirst($gameId)
    {
        $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->delete('BlueBearCoreBundle:Game\GameAction')
            ->where('action.game = :game')
            ->setMaxResults(1)
            ->setParameter('game', $gameId);
        $this
            ->getEntityManager()
            ->flush();
    }

    /**
     * @return GameActionRepository
     */
    public function getRepository()
    {
        return $this
            ->getEntityManager()
            ->getRepository('BlueBearCoreBundle:Game\GameAction');
    }
}
