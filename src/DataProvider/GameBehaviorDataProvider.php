<?php

namespace App\DataProvider;

use App\Engine\Exception\EngineException;
use App\Entity\Engine\GameObject;
use App\Repository\Engine\MovementBehaviorRepository;
use App\Repository\Engine\PositionBehaviorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class GameBehaviorDataProvider
{
    /**
     * @var MovementBehaviorRepository
     */
    private $movementBehaviorRepository;

    /**
     * @var PositionBehaviorRepository
     */
    private $positionBehaviorRepository;

    // TODO behavior repositories loading should be generic
    public function __construct(
        MovementBehaviorRepository $movementBehaviorRepository,
        PositionBehaviorRepository $positionBehaviorRepository
    ) {
        $this->movementBehaviorRepository = $movementBehaviorRepository;
        $this->positionBehaviorRepository = $positionBehaviorRepository;
    }

    public function get(GameObject $gameObject): Collection
    {
        $movementBehaviors = $this->movementBehaviorRepository->findBy([
            'gameObject' => $gameObject,
        ]);

        $positionBehaviors = $this->positionBehaviorRepository->findBy([
            'gameObject' => $gameObject,
        ]);

        return new ArrayCollection(array_merge($movementBehaviors, $positionBehaviors));
    }

    private function throwExceptionIfNull(string $reference, ?GameObject $gameObject)
    {
        if (null === $gameObject) {
            throw new EngineException('Game object "'.$reference.'" not found');
        }
    }
}
