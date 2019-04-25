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
 * @ORM\Table(name="campaign")
 * @ORM\Entity(repositoryClass="App\Entity\Content\CampaignRepository")
 */
class Campaign
{
    use Id, Nameable, Label, Descriptionable;
}
