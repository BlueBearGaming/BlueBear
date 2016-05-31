<?php

namespace BlueBear\CoreBundle\Entity\Game\Player;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="player")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Game\Player\PlayerRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Player
{
    use Id, Nameable, Timestampable;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\User\User")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * @ORM\Column(name="is_human", type="boolean")
     */
    protected $isHuman = false;

    /**
     * @ORM\Column(name="pseudonym", type="string", length=255)
     */
    protected $pseudonym;

    /**
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Army", mappedBy="player")
     */
    protected $armies;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled = false;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isIsHuman()
    {
        return $this->isHuman;
    }

    /**
     * @param boolean $isHuman
     */
    public function setIsHuman($isHuman)
    {
        $this->isHuman = $isHuman;
    }

    /**
     * @return mixed
     */
    public function getPseudonym()
    {
        return $this->pseudonym;
    }

    /**
     * @param mixed $pseudonym
     */
    public function setPseudonym($pseudonym)
    {
        $this->pseudonym = $pseudonym;
    }

    /**
     * @return mixed
     */
    public function getArmies()
    {
        return $this->armies;
    }

    /**
     * @param mixed $armies
     */
    public function setArmies($armies)
    {
        $this->armies = $armies;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

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
}
