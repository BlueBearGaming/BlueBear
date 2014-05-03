<?php


namespace BlueBear\CoreBundle\Entity\Behavior;


trait Sizable
{
    /**
     * @ORM\Column(type="float")
     */
    protected $width;

    /**
     * @ORM\Column(type="float")
     */
    protected $height;

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Return a string representing entity size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->height . 'x' . $this->width;
    }
} 