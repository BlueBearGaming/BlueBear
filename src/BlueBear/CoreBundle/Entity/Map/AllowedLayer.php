<?php


namespace BlueBear\CoreBundle\Entity\Map;

use Doctrine\ORM\Mapping as ORM;

/**
 * Each pencil are allowed to be drawn on certain pencil
 *
 * @ORM\Table(name="allowed_layer")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\LayerRepository")
 */
class AllowedLayer
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Pencil", inversedBy="allowedLayers")
     */
    protected $pencil;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Layer")
     */
    protected $layer;

    /**
     * @return mixed
     */
    public function getPencil()
    {
        return $this->pencil;
    }

    /**
     * @param mixed $pencil
     */
    public function setPencil($pencil)
    {
        $this->pencil = $pencil;
    }

    /**
     * @return mixed
     */
    public function getLayer()
    {
        return $this->layer;
    }

    /**
     * @param mixed $layer
     */
    public function setLayer($layer)
    {
        $this->layer = $layer;
    }
} 