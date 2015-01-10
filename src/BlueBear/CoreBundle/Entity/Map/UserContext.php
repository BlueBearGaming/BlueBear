<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserContext
 *
 * @ORM\Table(name="user_context")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\ContextRepository")
 */
class UserContext 
{
    use Timestampable;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\UserBundle\Entity\User", inversedBy="userContexts")
     * @ORM\Id()
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Context", inversedBy="userContexts")
     * @ORM\Id()
     */
    protected $context;
}