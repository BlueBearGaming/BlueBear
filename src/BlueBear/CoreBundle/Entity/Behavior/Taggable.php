<?php


namespace BlueBear\CoreBundle\Entity\Behavior;


trait Taggable
{
    /**
     * Tags
     *
     * @var string
     * @ORM\Column(type="text")
     */
    protected $tags = '';

    /**
     * Return item's tags
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set item's tags
     *
     * @param $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }
} 