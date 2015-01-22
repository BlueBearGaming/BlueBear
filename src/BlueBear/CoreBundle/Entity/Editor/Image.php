<?php


namespace BlueBear\CoreBundle\Entity\Editor;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image are used in the editor
 *
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Editor\ImageRepository")
 */
class Image extends Resource
{
    /**
     * @var \BlueBear\CoreBundle\Entity\Map\Pencil
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Pencil", mappedBy="image")
     */
    protected $pencil;

    /**
     * @var \BlueBear\CoreBundle\Entity\Map\PencilSet
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\PencilSet", mappedBy="sprite")
     */
    protected $pencilSet;

    public function getType() {
        return 'image';
    }
} 