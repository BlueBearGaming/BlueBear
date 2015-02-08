<?php


namespace BlueBear\CoreBundle\Entity\Editor;

use Doctrine\ORM\Query\Expr\Join;
use BlueBear\FileUploadBundle\Entity\ResourceRepository;

class ImageRepository extends ResourceRepository
{
    public function getQbForOrphans(Image $image = null)
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.pencil', 'p', Join::WITH, 'e.id = IDENTITY(p.image)')
            ->andWhere('p.id IS NULL');
        if ($image) {
            $qb->orWhere('e.id = :imageId')
                ->setParameter('imageId', $image->getId());
        }
        return $qb;
    }

    public function findOrphans(\DateTime $before = null)
    {
        if (!$before) {
            $before = new \DateTime;
            $before->modify('5 minutes ago');
        }

        $qb = $this->getQbForOrphans()
            ->andWhere('e.createdAt < :before')
            ->setParameters([
                'before' => $before,
            ]);
        return $qb->getQuery()->getResult();
    }
} 