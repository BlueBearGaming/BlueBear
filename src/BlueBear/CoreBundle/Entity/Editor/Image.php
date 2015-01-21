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
     * @var Image
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Pencil", mappedBy="image")
     */
    protected $pencil;

    public function getType() {
        return 'image';
    }
} 