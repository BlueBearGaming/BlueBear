<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @deprecated 
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
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Context", inversedBy="userContexts", cascade={"remove"})
     * @ORM\Id()
     */
    protected $context;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param mixed $context
     */
    public function setContext(Context $context)
    {
        $this->context = $context;
    }
}
