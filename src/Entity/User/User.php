<?php

namespace App\Entity\User;

use App\Entity\Behavior\Timestampable;

class User extends \FOS\UserBundle\Model\User
{
    use Timestampable;

    protected $id;

    protected $contexts;

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
    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * @param mixed $contexts
     */
    public function setContexts($contexts)
    {
        $this->contexts = $contexts;
    }
} 
