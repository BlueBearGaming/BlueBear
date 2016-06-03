<?php

namespace BlueBear\CoreBundle\Entity\Game\Save;

use BlueBear\CoreBundle\Entity\Game\Player\Player;
use Doctrine\ORM\EntityRepository;

class SaveRepository extends EntityRepository
{
    public function findForPlayer(Player $player)
    {
        return $this
            ->createQueryBuilder('save')
            ->innerJoin('save.context', 'context')
            ->innerJoin('context.player', 'player')
            ->where('player.id = :player_id')
            ->setParameter('player_id', $player->getId())
            ->getQuery()
            ->getResult()
        ;
    }
}
