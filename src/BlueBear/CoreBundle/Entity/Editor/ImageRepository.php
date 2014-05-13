<?php


namespace BlueBear\CoreBundle\Entity\Editor;

use Doctrine\ORM\EntityRepository;

class ImageRepository extends EntityRepository
{
    public function findOrphans($pencilId = 0)
    {
        $queryBuilder = $this
            ->createQueryBuilder('image')
            ->where('image.pencil IS NULL')
            ->orderBy('image.updatedAt', 'DESC');

        if ($pencilId) {
            $queryBuilder
                ->orWhere('image.pencil = :pencil_id')
                ->setParameter('pencil_id', $pencilId);
        }
        return $queryBuilder;
    }
} 