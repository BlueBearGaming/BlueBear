<?php

namespace App\Repository\Editor;

use App\Entity\Editor\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function getQbForOrphans(Image $image = null)
    {
        $qb = $this
            ->createQueryBuilder('e')
            ->leftJoin('e.pencil', 'p', Join::WITH, 'e.id = IDENTITY(p.image)')
            ->andWhere('p.id IS NULL')
        ;

        if ($image) {
            $qb
                ->orWhere('e.id = :imageId')
                ->setParameter('imageId', $image->getId())
            ;
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
