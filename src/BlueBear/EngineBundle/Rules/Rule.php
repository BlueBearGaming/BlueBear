<?php

namespace BlueBear\EngineBundle\Rules;


interface Rule
{
    /**
     * @return callable
     */
    public function getCallback();
}