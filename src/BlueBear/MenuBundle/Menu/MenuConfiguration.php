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
        } else {
            $this->template = 'BlueBearMenuBundle:Menu:main.html.twig';
        }
        if (array_key_exists('main_item', $menuConfiguration)) {
            $mainItemConfiguration = new ItemConfiguration();
            $mainItemConfiguration->hydrateFromConfiguration($menuConfiguration['main_item'], 'main');
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
        return $this->mainItemConfiguration != null;
    }
}