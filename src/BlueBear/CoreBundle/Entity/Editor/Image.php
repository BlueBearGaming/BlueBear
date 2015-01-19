<?php


namespace BlueBear\CoreBundle\Entity\Editor;

use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Image are used in the editor
 *
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Editor\ResourceRepository")
 */
class Image extends Resource
{
    use Nameable;
} 