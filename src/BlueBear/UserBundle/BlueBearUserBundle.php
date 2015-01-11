<?php

namespace BlueBear\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BlueBearUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
