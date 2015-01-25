<?php

namespace BlueBear\AdminBundle\Menu;

class MenuItem 
{
    protected $route;

    protected $parameters = [];

    protected $label;

    public function __construct($route, $parameters = [], $label = '')
    {
        $this->label = $label;
        $this->route = $route;
        $this->parameters = $parameters;
    }
}