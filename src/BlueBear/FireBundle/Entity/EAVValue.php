<?php

namespace BlueBear\FireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sidus\EAVModelBundle\Entity\Value;

/**
 * @ORM\Table(name="fire_eav_value")
 * @ORM\Entity(repositoryClass="Sidus\EAVModelBundle\Entity\DataRepository")
 */
class EAVValue extends Value
{
}
