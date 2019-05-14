<?php

namespace App\Factory\Model;

use App\Contracts\Factory\ModelFactoryInterface;
use App\Contracts\Model\ModelInterface;
use App\Engine\Exception\EngineException;
use App\Model\Map\Movement;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelFactory implements ModelFactoryInterface
{
    private $mapping = [];

    public function __construct(array $mapping = [])
    {
        $this->mapping = array_merge([
            'movement' => Movement::class,
        ], $mapping);
    }

    public function create(string $name, array $data): ModelInterface
    {
        if (!key_exists($name, $this->mapping)) {
            throw new EngineException('Invalid model mapping');
        }

        if ('movement' === $name) {
            $resolver = new OptionsResolver();


            $model = new Movement();
        }

        dump($name, $data);
        die;
        // TODO: Implement create() method.
    }
}
