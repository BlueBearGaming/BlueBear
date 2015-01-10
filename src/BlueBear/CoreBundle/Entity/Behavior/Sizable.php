<?php

namespace BlueBear\CoreBundle\Entity\Behavior;

use JMS\Serializer\Annotation\Expose;

/**
 * Sizable
 *
 * Capacity to have a size, ie a width and an height
 */
trait Sizable
{
    /**
     * Entity width
     *
     * @ORM\Column(type="integer")
     * @Expose()
     */
    protected $width = 0;

    /**
     * Entity height
     *
     * @ORM\Column(type="integer")
     * @Expose()
     */
    protected $height = 0;

    /**
     * Return entity height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set entity height
     *
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Return entity width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set entity width
     *
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Return a string representing entity size (string concatenation:  width . 'x' . height)
     *
     * @return string
     */
    public function getSize()
    {
        return $this->height . 'x' . $this->width;
    }
} 