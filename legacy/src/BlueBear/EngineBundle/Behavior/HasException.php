<?php

namespace BlueBear\EngineBundle\Behavior;

use Exception;

trait HasException
{
    public function throwUnless($condition, $message = 'Engine error')
    {
        if (!$condition) {
            throw new Exception($message);
        }
    }
}