<?php


namespace BlueBear\CoreBundle\Entity\Editor;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ImageRepository extends EntityRepository
{
    /**
     * Find orphans images, i.e. images with no linked pencil
     *
     * @param int $pencilId
     * @return QueryBuilder
     */
    public function findOrphans($pencilId = 0)
    {
        $subQueryBuilder = new QueryBuilder($this->_em);
        $queryBuilder = $this->createQueryBuilder('image');

        $queryBuilder
            ->where($queryBuilder->expr()->notIn('image.id',
                $subQueryBuilder
                    ->select('linkedImage.id')
                    ->from('BlueBear\CoreBundle\Entity\Map\Pencil', 'pencil')
                    ->join('pencil.image', 'linkedImage')
                    ->getDQL()
            ))
            ->orderBy('image.updatedAt', 'DESC');

        if ($pencilId) {
            $queryBuilder
                ->orWhere('image.pencil = :pencil_id')
                ->setParameter('pencil_id', $pencilId);
        }
        return $queryBuilder;
    }
} 