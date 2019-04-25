<?php

namespace App\Entity\Editor;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Behavior\Descriptionable;
use App\Entity\Behavior\Id;
use App\Entity\Behavior\Label;
use App\Entity\Behavior\Nameable;

/**
 * Story
 *
 * @ORM\Table(name="story")
 * @ORM\Entity(repositoryClass="App\Entity\Content\StoryRepository")
 */
class Story
{
    use Id, Nameable, Label, Descriptionable;
}
