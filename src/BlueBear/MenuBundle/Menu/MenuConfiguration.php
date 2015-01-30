<?php

namespace BlueBear\MenuBundle\Menu;


class MenuConfiguration
{
    protected $name;

    protected $template;

    /**
     * @var ItemConfiguration
     */
    protected $mainItemConfiguration;

    public function hydrateFromConfiguration($menuConfiguration = [], $menuName)
    {
        $this->name = $menuName;

        if (array_key_exists('template', $menuConfiguration)) {
            $this->template = $menuConfiguration['template'];
        }
        if (array_key_exists('main_item', $menuConfiguration)) {
            $mainItemConfiguration = new ItemConfiguration();
            $mainItemConfiguration->hydrateFromConfiguration($menuConfiguration, 'main');
            $this->mainItemConfiguration = $mainItemConfiguration;
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
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return ItemConfiguration
     */
    public function getMainItemConfiguration()
    {
        return $this->mainItemConfiguration;
    }

    /**
     * @return bool
     */
    public function hasMainItemConfiguration()
    {
        return filter_var($this->mainItemConfiguration, FILTER_VALIDATE_BOOLEAN);
    }
}