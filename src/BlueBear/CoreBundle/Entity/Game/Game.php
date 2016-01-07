<?php

namespace BlueBear\CoreBundle\Entity\Game;

use BlueBear\BaseBundle\Entity\Behaviors\Id;
use BlueBear\BaseBundle\Entity\Behaviors\Nameable;
use BlueBear\BaseBundle\Entity\Behaviors\Timestampable;
use BlueBear\CoreBundle\Entity\Map\Context;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="game", indexes={@ORM\Index(name="hash_idx", columns={"hash"})})
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Game\GameRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Game
{
    use Id, Nameable, Timestampable;

    /**
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Context")
     * @ORM\JoinColumn(name="context_id")
     */
    protected $context;

    /**
     * @ORM\Column(name="hash", type="string", length=255)
     */
    protected $hash;

    /**
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Game\GameAction", cascade={"persist"}, mappedBy="game")
     * @var ArrayCollection
     */
    protected $actionStack;

    public function __construct()
    {
        $this->actionStack = new ArrayCollection();
    }

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param mixed $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return mixed
     */
    public function getActionStack()
    {
        return $this->actionStack;
    }

    /**
     * @param mixed $actionStack
     */
    public function setActionStack($actionStack)
    {
        $this->actionStack = $actionStack;
    }

    public function addActionToStack(GameAction $gameAction)
    {
        $this->actionStack->add($gameAction);
    }
}