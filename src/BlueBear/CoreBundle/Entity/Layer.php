<?php

namespace BlueBear\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A layer of the map, containing a collection of positioned mapitems
 *
 * @ORM\Table(name="layer")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\LayerRepository")
 */
class Layer {

    /**
     * Unique name
     * @ORM\Id
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    protected $id;
    
    /**
     * Layer type (terrain, props, buildings, units...)
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $type = 'background';

}
