<?php

namespace App\Manager\Engine;

use App\Engine\Exception\EngineException;
use App\Entity\Engine\GameObject;
use App\Repository\Engine\GameObjectRepository;

class GameObjectManager
{
    /**
     * @var GameObjectRepository
     */
    private $gameObjectRepository;

    public function __construct(GameObjectRepository $gameObjectRepository)
    {
        $this->gameObjectRepository = $gameObjectRepository;
    }

    public function get(string $reference, string $type): GameObject
    {
        $object = $this->gameObjectRepository->findOneBy([
            'reference' => $reference,
            'type' => $type,
        ]);
        $this->throwExceptionIfNull($reference, $object);

        return $object;
    }

    private function throwExceptionIfNull(string $reference, ?GameObject $gameObject)
    {
        if (null === $gameObject) {
            throw new EngineException('Missing game object "'.$reference.'" not found');
        }
    }
}
