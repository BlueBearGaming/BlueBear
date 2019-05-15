<?php

namespace App\Handler\Engine;

use App\Contracts\Handler\ModelHandlerInterface;
use App\Contracts\Model\ModelInterface;
use App\Model\Map\Movement;

class MovementHandler implements ModelHandlerInterface
{
    /**
     * @param ModelInterface|Movement $model
     */
    public function handle(ModelInterface $model): void
    {
        
        
        die('ko');

    }

    public function supports(string $class): bool
    {
        return Movement::class === $class;
    }
}
