<?php

namespace App\Entity\Engine;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class GameEntity
{
    private $id;

    private $reference;

    private $description = '';

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @var GameBehavior[]|Collection
     */
    private $behaviors;

    /**
     * @var string
     */
    private $type;

    public function __construct(string $type)
    {
        $this->reference = Uuid::uuid4()->toString();
        $this->behaviors = new ArrayCollection();
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getDescription(): string
    {
        return $this->description;
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
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return GameBehavior[]|Collection
     */
    public function getBehaviors(): Collection
    {
        return $this->behaviors;
    }

    public function addBehavior(GameBehavior $behavior)
    {
        $this->behaviors->add($behavior);
    }

    public function removeBehavior(GameBehavior $behavior)
    {
        $this->behaviors->removeElement($behavior);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
