<?php

namespace App\Entity\Behavior;

/**
 * Sizable
 *
 * Capacity to have a size, ie a width and an height
 */
trait Sizable
{
    /**
     * Entity width
     */
    protected $width = 1;

    /**
     * Entity height
     */
    protected $height = 1;

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
