<?php

namespace BlueBear\CoreBundle\Entity\Map;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Doctrine\ORM\EntityRepository;

class ContextRepository extends EntityRepository
{
    /**
     * Return a context with its map item from a starting position to a ending position
     *
     * @param $contextId
     * @param $startingX
     * @param $startingY
     * @param $endingX
     * @param $endingY
     * @return QueryBuilder
     * @throws Exception
     */
    public function findWithLimit($contextId, $startingX, $startingY, $endingX, $endingY)
    {
        // here we need a left join because we want to have a context even if it has no item, but if there are items,
        // we should limit them to starting and ending point. Unfortunately, Doctrine does not allow parameters in join
        // condition, so we should verify that those parameters are integer
        if (!is_int($startingX) or !is_int($startingY) or !is_int($endingX) or !is_int($endingY)) {
            throw new Exception('Starting and ending position should be integer');
        }
        return $this
            ->createQueryBuilder('context')
            ->addSelect('mapItems')
            ->leftJoin(
                'context.mapItems',
                'mapItems',
                Join::WITH,
                "mapItems.context = context AND mapItems.x >= {$startingX} AND mapItems.y >= {$startingY}
                    AND mapItems.x <= {$endingX} AND mapItems.y <= {$endingY}"
            )
            ->where('context.id = :context_id')
            ->setParameters([
                'context_id' => $contextId
            ]);
    }
}
