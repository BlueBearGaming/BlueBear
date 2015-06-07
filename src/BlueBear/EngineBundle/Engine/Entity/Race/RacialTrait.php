<?php

namespace BlueBear\EngineBundle\Engine\Entity\Race;

use BlueBear\EngineBundle\Engine\Annotation as Game;

class RacialTrait
{
    /**
     * @var string
     * @Game\Id()
     */
    protected $code;

    /**
     * @Game\Relation(class="BlueBear\EngineBundle\Engine\Entity\Attribute\AttributeSetter", type="OneToMany")
     */
    protected $attributeSetters;

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

    /**
     * @return mixed
     */
    public function getAttributeSetters()
    {
        return $this->attributeSetters;
    }

    /**
     * @param mixed $attributeSetters
     */
    public function setAttributeSetters($attributeSetters)
    {
        $this->attributeSetters = $attributeSetters;
    }
}
