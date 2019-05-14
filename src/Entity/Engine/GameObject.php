<?php

namespace App\Entity\Engine;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class GameObject
{
    protected $id;

    protected $reference;

    protected $description = '';

    /**
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @var DateTime
     */
    protected $updatedAt;

    /**
     * @var GameBehavior[]|Collection
     */
    protected $behaviors;

    /**
     * @var string
     */
    protected $type;

    protected $x = 0;

    protected $y = 0;

    protected $z = 0;

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

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX(int $x): void
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY(int $y): void
    {
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getZ(): int
    {
        return $this->z;
    }

    /**
     * @param int $z
     */
    public function setZ(int $z): void
    {
        $this->z = $z;
    }
}
