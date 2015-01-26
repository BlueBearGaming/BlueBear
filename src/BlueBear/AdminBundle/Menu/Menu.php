<?php

namespace BlueBear\AdminBundle\Menu;

class Menu 
{
    protected $name;

    protected $items = [];

    protected $template;

    protected $mainItem;

    public function __construct($name, $template = '', $mainItem = '')
    {
        $this->name = $name;
        $this->template = $template;
        $this->mainItem = $mainItem;
    }

    public function addItem(MenuItem $menuItem)
    {
        $this->items[] = $menuItem;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return MenuItem
     */
    public function getItems()
    {
        return $this->items;
    }

    public function getMainItem()
    {
        return $this->mainItem;
    }
}