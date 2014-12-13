<?php

namespace BlueBear\CoreBundle\Entity\Content;

use Doctrine\ORM\Mapping as ORM;
use BlueBear\CoreBundle\Entity\Behavior\Descriptionable;
use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;

/**
 * Story
 *
 * @ORM\Table(name="story")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Content\StoryRepository")
 */
class Story
{
    use Id, Nameable, Label, Descriptionable;
}