<?php

namespace App\Contracts\Factory;

use App\Contracts\Model\ModelInterface;

interface ModelFactoryInterface
{
    public function create(string $name, array $data): ModelInterface;
}
