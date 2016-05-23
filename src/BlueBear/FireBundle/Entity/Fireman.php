<?php

namespace BlueBear\FireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="fire_fireman")
 */
class Fireman
{
    public $id;

    public $name;

    public $movement;
}
