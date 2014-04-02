<?php

namespace BlueBear\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The map.
 *
 * @ORM\Table(name="map")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\MapRepository")
 */
class Map {

    /**
     * Unique name
     * @ORM\Id
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    protected $id;
    
    /**
     * @var \BlueBear\CoreBundle\Entity\MapItem[]
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\MapItem", mappedBy="map", cascade={"ALL"}, indexBy="items")
     */
    protected $items;

}
