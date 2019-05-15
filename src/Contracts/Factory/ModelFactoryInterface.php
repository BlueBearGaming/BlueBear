<?php

namespace App\Contracts\Factory;

use App\Contracts\Model\ModelInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ModelFactoryInterface
{
    public function create(string $modelName, array $data): ModelInterface;

    public function supports(string $modelName): bool;

    public function configure(string $modelName, OptionsResolver $resolver): void;
}
