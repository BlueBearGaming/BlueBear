<?php

namespace BlueBear\FireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sidus\EAVModelBundle\Entity\Data;

/**
 * @ORM\Table(name="fire_eav_entity")
 * @ORM\Entity(repositoryClass="Sidus\EAVModelBundle\Entity\DataRepository")
 */
class EAVEntity extends Data
{
}
