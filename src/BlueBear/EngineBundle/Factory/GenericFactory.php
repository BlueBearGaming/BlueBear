<?php

namespace BlueBear\EngineBundle\Factory;

use BlueBear\BaseBundle\Behavior\ContainerTrait;

class GenericFactory
{
    use ContainerTrait;

    public function import()
    {
        return true;
    }

}
