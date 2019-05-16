<?php

namespace App\Repository\Engine;

use App\Entity\Engine\AbstractBehavior;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GameBehaviorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractBehavior::class);
    }
}