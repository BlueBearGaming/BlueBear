<?php

namespace BlueBear\AdminBundle\Menu;

class Menu 
{
    protected $name;

    protected $items = [];

    protected $template;

    public function __construct($name, $template = '')
    {
        $this->name = $name;
        $this->template = $template;
    }

    public function addItem(MenuItem $menuItem)
    {
        $this->items[] = $menuItem;
    }
}