<?php

namespace App\Factory\Model\Engine;

use App\Contracts\Factory\ModelFactoryInterface;
use App\Contracts\Model\ModelInterface;
use App\Entity\Engine\GameObject;
use App\Manager\Engine\GameObjectManager;
use App\Model\Map\Movement;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovementFactory implements ModelFactoryInterface
{
    /**
     * @var GameObjectManager
     */
    private $gameObjectManager;

    public function __construct(GameObjectManager $gameObjectManager)
    {
        $this->gameObjectManager = $gameObjectManager;
    }

    public function create(string $modelName, array $data): ModelInterface
    {
        $source = $this->gameObjectManager->get($data['source'], GameObject::TYPE_MAP_ITEM);
        $destination = $this->gameObjectManager->get($data['destination'], GameObject::TYPE_MAP_ITEM);
        $movable = $this->gameObjectManager->get($data['movable'], GameObject::TYPE_UNIT);

        return new Movement($source, $destination, $movable);
    }

    public function supports(string $modelName): bool
    {
        return 'movement' === $modelName;
    }

    public function configure(string $modelName, OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired([
                'source',
                'destination',
                'movable',
            ])
            ->setAllowedTypes('source', 'string')
            ->setAllowedTypes('destination', 'string')
            ->setAllowedTypes('movable', 'string')
        ;
    }
}
