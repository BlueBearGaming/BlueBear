<?php

namespace BlueBear\DungeonBundle\Entity\ORM;

use Doctrine\ORM\Mapping as ORM;

/**
 * Character
 *
 * @ORM\Table(name="dungeon_character")
 * @ORM\Entity(repositoryClass="BlueBear\DungeonBundle\Entity\ORM\CharacterRepository")
 */
class Character
{
    /**
     * Entity id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(name="race", type="string", length=255)
     */
    public $race;

    /**
     * @ORM\Column(name="class", type="string", length=255)
     */
    public $class;

    /**
     * @ORM\Column(name="attributes", type="array")
     */
    public $attributes;

    /**
     * @ORM\Column(name="hit_points", type="integer")
     */
    public $hitPoints;
}
