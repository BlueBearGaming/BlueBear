<?php

namespace App\Handler\Model;

use App\Contracts\Handler\ModelHandlerInterface;
use App\Contracts\Model\ModelInterface;
use App\Model\Map\Movement;

class ModelHandler implements ModelHandlerInterface
{
    /**
     * @var ModelHandlerInterface[]
     */
    private $handlers = [];

    public function handle(ModelInterface $model): void
    {
        foreach ($this->handlers as $handler) {
            if (!$handler->supports(get_class($model))) {
                continue;
            }
            $handler->handle($model);
        }
    }

    public function supports(string $class): bool
    {
        return in_array($class, [
            Movement::class,
        ]);
    }

    public function addHandler(ModelHandlerInterface $handler)
    {
        $this->handlers[] = $handler;
    }
}
