<?php

namespace BlueBear\MenuBundle\Menu;

class Menu 
{
    protected $name;

    protected $items = [];

    protected $template;

    protected $mainItem;

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

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @param mixed $mainItem
     */
    public function setMainItem(MenuItem $mainItem)
    {
        $this->mainItem = $mainItem;
    }
}