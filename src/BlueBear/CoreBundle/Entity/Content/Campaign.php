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
 * @ORM\Table(name="campaign")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Content\CampaignRepository")
 */
class Campaign
{
    use Id, Nameable, Label, Descriptionable;
}