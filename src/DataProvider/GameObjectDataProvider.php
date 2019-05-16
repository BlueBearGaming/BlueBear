<?php

namespace App\DataProvider;

use App\Engine\Exception\EngineException;
use App\Entity\Engine\GameObject;
use App\Repository\Engine\GameObjectRepository;

class GameObjectDataProvider
{
    /**
     * @var GameObjectRepository
     */
    private $gameObjectRepository;

    public function __construct(GameObjectRepository $gameObjectRepository)
    {
        $this->gameObjectRepository = $gameObjectRepository;
    }

    public function get(string $reference): GameObject
    {
        $object = $this->gameObjectRepository->get($reference);
        $this->throwExceptionIfNull($reference, $object);

        return $object;
    }

    private function throwExceptionIfNull(string $reference, ?GameObject $gameObject)
    {
        if (null === $gameObject) {
            throw new EngineException('Game object "'.$reference.'" not found');
        }
    }
}
