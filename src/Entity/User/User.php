<?php

namespace App\Entity\User;

use App\Entity\Behavior\Timestampable;

class User extends \FOS\UserBundle\Model\User
{
    use Timestampable;

    protected $id;

    protected $userContexts;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserContexts()
    {
        return $this->userContexts;
    }

    /**
     * @param mixed $userContexts
     */
    public function setUserContexts($userContexts)
    {
        $this->userContexts = $userContexts;
    }
} 
