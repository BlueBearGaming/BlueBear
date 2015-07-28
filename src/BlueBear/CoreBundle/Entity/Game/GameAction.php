<?php

namespace BlueBear\CoreBundle\Entity\Game;

use BlueBear\BaseBundle\Entity\Behaviors\Id;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="game_action")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Game\GameActionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class GameAction
{
    use Id, Nameable, Timestampable;

    /**
     * @ORM\Column(name="action", type="string")
     */
    protected $action;

    /**
     * @ORM\Column(name="data", type="text", nullable=true)
     */
    protected $data;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Game\Game", inversedBy="actionStack")
     */
    protected $game;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\EngineBundle\Entity\EntityInstance")
     * @ORM\JoinColumn(name="entity_instance_id", nullable=true)
     */
    protected $entityInstance;

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param mixed $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getEntityInstance()
    {
        return $this->entityInstance;
    }

    /**
     * @param mixed $entityInstance
     */
    public function setEntityInstance($entityInstance)
    {
        $this->entityInstance = $entityInstance;
    }
}
