<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Imageable;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\ORM\Mapping as ORM;

/**
 * A layer of the map, containing a collection of positioned mapItems
 *
 * @ORM\Table(name="layer")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\LayerRepository")
 */
class Layer
{
    use Id, Nameable, Label, Typeable, Imageable;
}