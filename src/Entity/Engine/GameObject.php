<?php

namespace App\Entity\Engine;

use App\Contracts\Engine\BehaviorInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class GameObject
{
    const TYPE_MAP_ITEM = 'map_item';
    const TYPE_UNIT = 'unit';
    const TYPE_OTHER = 'other';

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
     * @var AbstractBehavior[]|Collection
     */
    protected $behaviors;

    /**
     * @var string
     */
    protected $type;

    public function __construct(string $type)
    {
        $this->reference = Uuid::uuid4()->toString();
        $this->behaviors = new ArrayCollection();
        $this->type = $type;
    }

    public function load(): void
    {
        if (null === $this->behaviors) {
            $this->behaviors = new ArrayCollection();
        }
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
     * @return AbstractBehavior[]|Collection
     */
    public function getBehaviors(): Collection
    {
        return $this->behaviors;
    }

    public function addBehaviors(Collection $behaviors)
    {
        foreach ($behaviors as $behavior) {
            $this->addBehavior($behavior);
        }
    }

    public function addBehavior(BehaviorInterface $behavior)
    {
        if (!$this->behaviors->contains($behavior)) {
            $this->behaviors->add($behavior);
        }
    }

    public function removeBehavior(BehaviorInterface $behavior)
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
