<?php


namespace BlueBear\CoreBundle\Entity\Behavior;

use FOS\UserBundle\Model\UserInterface;

class Owned
{
    /**
     * @ORM\ManyToOne
     * @var UserInterface
     */
    protected $user;
} 