<?php

namespace BlueBear\FireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sidus\EAVModelBundle\Entity\AbstractData;

/**
 * @ORM\Table(name="fire_eav_entity")
 * @ORM\Entity(repositoryClass="LAG\AdminEAVBridgeBundle\Repository\DoctrineEAVRepository")
 */
class EAVEntity extends AbstractData
{
}
