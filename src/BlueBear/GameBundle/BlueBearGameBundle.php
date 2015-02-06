<?php

namespace BlueBear\GameBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BlueBearGameBundle extends Bundle
{
    public function getParent()
    {
        return 'BlueBearCoreBundle';
    }
}