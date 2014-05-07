<?php

namespace BlueBear\CoreBundle\Entity\Map;

use Doctrine\ORM\Mapping as ORM;

/**
 * A layer of the map, containing a collection of positioned mapItems
 *
 * @ORM\Table(name="layer")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\LayerRepository")
 */
class Layer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Unique name
     *
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    protected $name;
    
    /**
     * Layer type (terrain, props, buildings, units...)
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $type = 'background';
}
