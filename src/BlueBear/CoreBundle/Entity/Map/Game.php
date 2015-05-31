<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\BaseBundle\Entity\Behaviors\Id;
use BlueBear\BaseBundle\Entity\Behaviors\Nameable;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\GameRepository")
 */
class Game
{
    use Id, Nameable;

    /**
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Context")
     * @ORM\JoinColumn(name="initial_context_id")
     */
    protected $initialContext;

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
}
