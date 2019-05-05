<?php

namespace App\Entity\Behavior;

trait Taggable
{
    /**
     * @var array
     */
    protected $tags = [];

    /**
     * Return item's tags.
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Set item's tags.
     *
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }
} 
