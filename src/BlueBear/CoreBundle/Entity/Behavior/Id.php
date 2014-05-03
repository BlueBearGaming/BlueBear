<?php

namespace BlueBear\CoreBundle\Entity\Behavior;

trait Id
{
    /**
     *
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
}