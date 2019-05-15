<?php

namespace App\Factory\Model;

use App\Contracts\Factory\ModelFactoryInterface;
use App\Contracts\Model\ModelInterface;
use App\Engine\Exception\EngineException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelFactory implements ModelFactoryInterface
{
    /**
     * @var ModelFactoryInterface[]
     */
    private $factories;

    public function __construct(array $factories = [])
    {
        $this->factories = $factories;
    }

    public function create(string $modelName, array $data): ModelInterface
    {
        foreach ($this->factories as $factory) {
            if ($this->supports($modelName)) {
                return $factory->create($modelName, $data);
            }
        }

        throw new EngineException('No model factory found to create the model "'.$modelName.'"');
    }

    public function supports(string $modelName): bool
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($modelName)) {
                return true;
            }
        }

        return false;
    }

    public function configure(string $modelName, OptionsResolver $resolver): void
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($modelName)) {
                $factory->configure($modelName, $resolver);
            }
        }
    }
}
