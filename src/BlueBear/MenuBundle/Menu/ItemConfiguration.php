<?php

namespace BlueBear\MenuBundle\Menu;


class ItemConfiguration
{
    protected $name;

    protected $title;

    protected $route;

    protected $parameters;

    public function hydrateFromConfiguration($itemConfiguration = [], $itemName)
    {
        $this->name = $itemName;

        if (array_key_exists('title', $itemConfiguration)) {
            $this->title = $itemConfiguration['title'];
        }
        if (array_key_exists('route', $itemConfiguration)) {
            $this->route = $itemConfiguration['route'];
        }
        if (array_key_exists('parameters', $itemConfiguration)) {
            $this->parameters = $itemConfiguration['parameters'];
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}