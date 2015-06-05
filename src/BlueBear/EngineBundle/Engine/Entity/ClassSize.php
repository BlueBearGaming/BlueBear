<?php

namespace BlueBear\RogueBearBundle\Engine\Entity;


class ClassSize
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
