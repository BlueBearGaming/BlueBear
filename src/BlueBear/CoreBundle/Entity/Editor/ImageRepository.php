<?php


namespace BlueBear\CoreBundle\Entity\Editor;

use Doctrine\ORM\EntityRepository;

class ImageRepository extends EntityRepository
{
    public function findOrphans()
    {
        return $this
            ->createQueryBuilder('image')
            ->where('image.pencil IS NULL')
            ->orderBy('image.updatedAt', 'DESC');
    }
} 