<?php


namespace BlueBear\CoreBundle\Entity\Editor;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use Doctrine\ORM\Mapping as ORM;

/**
 * Image are used in the editor
 *
 * @ORM\Table(name="editor_image")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Editor\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    const IMAGE_TYPE_SINGLE_IMAGE = 'image.type.single_image';
    const IMAGE_TYPE_RPG_MAKER_SPRITE = 'image.type.rpg_maker_sprite';

    use Id, Nameable, Timestampable;

    /**
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Pencil")
     */
    protected $pencil;

    /**
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Editor\Resource")
     */
    protected $resource;

    public function getPencil()
    {
        return $this->pencil;
    }

    public function setPencil($pencil)
    {
        $this->pencil = $pencil;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }
} 