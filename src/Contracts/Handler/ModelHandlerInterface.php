<?php

namespace App\Contracts\Handler;

use App\Contracts\Model\ModelInterface;

interface ModelHandlerInterface
{
    public function handle(ModelInterface $model): void;

    public function supports(string $class): bool;
}
