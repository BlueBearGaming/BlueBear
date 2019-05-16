<?php

namespace App\Factory\Model\Engine;

use App\Contracts\Factory\ModelFactoryInterface;
use App\Contracts\Model\ModelInterface;
use App\DataProvider\GameObjectDataProvider;
use App\Model\Map\Movement;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovementFactory implements ModelFactoryInterface
{
    /**
     * @var GameObjectDataProvider
     */
    private $gameObjectDataProvider;

    public function __construct(GameObjectDataProvider $gameObjectDataProvider)
    {
        $this->gameObjectDataProvider = $gameObjectDataProvider;
    }

    public function create(string $modelName, array $data): ModelInterface
    {
        $source = $this->gameObjectDataProvider->get($data['source']);
        $destination = $this->gameObjectDataProvider->get($data['destination']);
        $movable = $this->gameObjectDataProvider->get($data['movable']);

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
