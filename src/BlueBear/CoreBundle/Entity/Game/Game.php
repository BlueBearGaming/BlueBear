<?php

namespace BlueBear\CoreBundle\Entity\Game;

use BlueBear\BaseBundle\Entity\Behaviors\Id;
use BlueBear\BaseBundle\Entity\Behaviors\Nameable;
use BlueBear\BaseBundle\Entity\Behaviors\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="game", indexes={@ORM\Index(name="hash_idx", columns={"hash"})})
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Game\GameRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Game
{
    use Id, Nameable, Timestampable;

    /**
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Context")
     * @ORM\JoinColumn(name="initial_context_id")
     */
    protected $initialContext;

    /**
     * @ORM\Column(name="hash", type="string", length=255)
     */
    protected $hash;

    /**
     * @return mixed
     */
    public function getInitialContext()
    {
        return $this->initialContext;
    }

    /**
     * @param mixed $initialContext
     */
    public function setInitialContext($initialContext)
    {
        $this->initialContext = $initialContext;
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
}
