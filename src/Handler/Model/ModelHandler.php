<?php

namespace App\Handler\Model;

use App\Contracts\Handler\ModelHandlerInterface;
use App\Contracts\Model\ModelInterface;

class ModelHandler implements ModelHandlerInterface
{
    /**
     * @var ModelHandlerInterface[]
     */
    private $handlers = [];

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

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
        foreach ($this->handlers as $handler) {
            if ($handler->supports($class)) {
                return true;
            }
        }

        return false;
    }
}
