<?php

namespace BlueBear\AdminBundle\Admin;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AdminConfig
{
    public $controllerName;

    public $entityName;

    public $managerConfiguration;

    public $formType;

    public $maxPerPage;

    public $layout;

    public $blocksTemplate;

    public $actions;

    public function hydrateFromConfiguration(array $adminConfiguration, ContainerInterface $container)
    {
        // defaults values
        if (!array_key_exists('max_per_page', $adminConfiguration)) {
            // by default, 50 items per page
            $adminConfiguration['max_per_page'] = $container->getParameter('bluebear.admin.max_per_page');
        }
        if (!array_key_exists('block_templates', $adminConfiguration)) {
            // by default, we take the general value
            $adminConfiguration['block_templates'] = $container->getParameter('bluebear.admin.blocks_template');
        }
        if (!array_key_exists('layout', $adminConfiguration)) {
            // by default, we take the general value
            $adminConfiguration['layout'] = $container->getParameter('bluebear.admin.layout');
        }
        if (!array_key_exists('manager', $adminConfiguration)) {
            $adminConfiguration['manager'] = [];
        }
        // general values
        $adminConfigArray['block_templates'] = $container->getParameter('bluebear.admin.blocks_template');
        $this->controllerName = $adminConfiguration['controller'];
        $this->entityName = $adminConfiguration['entity'];
        $this->formType = $adminConfiguration['form'];;
        $this->blocksTemplate = $adminConfiguration['block_templates'];
        $this->actions = $adminConfiguration['actions'];
        $this->layout = $adminConfiguration['layout'];
        $this->maxPerPage = $adminConfiguration['max_per_page'];
        $this->managerConfiguration = $adminConfiguration['manager'];
    }
}