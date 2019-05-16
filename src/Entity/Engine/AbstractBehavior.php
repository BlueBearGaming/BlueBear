<?php

namespace App\Entity\Engine;

use App\Contracts\Engine\BehaviorInterface;
use DateTime;
use Ramsey\Uuid\Uuid;

abstract class AbstractBehavior implements BehaviorInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var GameObject
     */
    protected $gameObject;

    /**
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @var DateTime
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->reference = Uuid::uuid4();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return GameObject
     */
    public function getGameObject(): GameObject
    {
        return $this->gameObject;
    }

    /**
     * @param GameObject $gameObject
     */
    public function setGameObject(GameObject $gameObject): void
    {
        $this->gameObject = $gameObject;
    }
}
