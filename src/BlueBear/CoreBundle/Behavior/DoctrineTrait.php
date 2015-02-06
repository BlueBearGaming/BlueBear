<?php

namespace BlueBear\CoreBundle\Behavior;

use Doctrine\Bundle\DoctrineBundle\Registry;

trait DoctrineTrait
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @param $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }
}