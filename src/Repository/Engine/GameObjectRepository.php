<?php

namespace App\Repository\Engine;

use App\Entity\Engine\GameObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GameObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameObject::class);
    }

    public function get(string $reference): ?GameObject
    {
        return $this
            ->createQueryBuilder('game_object')
            ->where('game_object.reference = :reference')
            ->setParameters([
                'reference' => $reference,
            ])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
