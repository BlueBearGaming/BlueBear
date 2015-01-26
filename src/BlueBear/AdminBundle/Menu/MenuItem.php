<?php

namespace BlueBear\AdminBundle\Menu;

class MenuItem 
{
    protected $route;

    protected $parameters = [];

    protected $label;

    public function __construct($label, $route, $parameters = [])
    {
        $this->label = $label;
        $this->route = $route;
        $this->parameters = $parameters;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getLabel()
    {
        return $this->label;
    }
}