<?php

namespace BlueBear\DungeonBundle\Entity;

use BlueBear\DungeonBundle\Annotation as Game;

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
