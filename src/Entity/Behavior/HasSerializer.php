<?php

namespace App\Entity\Behavior;

use JMS\Serializer\Serializer;

trait HasSerializer
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @param Serializer $serializer
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }
}
