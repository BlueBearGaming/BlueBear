<?php


namespace BlueBear\CoreBundle\Entity\Editor;

use Doctrine\ORM\EntityRepository;

class ItemRepository extends EntityRepository
{
    /**
     * Return uncompleted items
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findUncompleted()
    {
        return $this->createQueryBuilder('item')
            ->where('item.displayName IS NULL')
            ->orWhere('item.description IS NULL')
            ->orWhere('item.layer IS NULL')
            ->orWhere('item.type IS NULL')
            ->orWhere('item.tags IS NULL');
    }
} 