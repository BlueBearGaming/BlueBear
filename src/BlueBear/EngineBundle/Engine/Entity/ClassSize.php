<?php

namespace BlueBear\EngineBundle\Engine\Entity;

use BlueBear\EngineBundle\Engine\Annotation as Game;

class ClassSize
{
    /**
     * @var string
     * @Game\Id
     */
    protected $code;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}
