<?php

namespace BlueBear\UserBundle\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Id;

class User
{
    use Id;

    protected $firstName;

    protected $lastName;

    protected $email;
} 