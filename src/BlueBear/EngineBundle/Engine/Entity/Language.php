<?php

namespace BlueBear\RogueBearBundle\Engine\Entity;


class Language
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
