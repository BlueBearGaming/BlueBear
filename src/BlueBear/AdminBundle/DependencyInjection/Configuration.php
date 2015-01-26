<?php

namespace BlueBear\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('blue_bear_admin');

        $rootNode
            ->children()
                ->scalarNode('layout')->end()
                // admins configuration
                ->arrayNode('admins')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('entity')->end()
                            ->scalarNode('form')->end()
                            ->scalarNode('controller')->defaultValue('BlueBearAdminBundle:Generic')->end()
                            ->arrayNode('actions')
                                ->defaultValue([
                                    ['name' => 'list'],
                                    ['name' => 'create'],
                                    ['name' => 'edit'],
                                    ['name' => 'edit']
                                ])
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('name')->end()
                                        ->scalarNode('label')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                // menus configurations
                ->arrayNode('menus')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('template')->defaultValue('BlueBearAdminBundle:Menu:main_menu.html.twig') ->end()
                            ->arrayNode('main_item')
                                ->children()
                                    ->scalarNode('route')->end()
                                    ->scalarNode('label')->end()
                                ->end()
                            ->end()
                            ->arrayNode('items')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('admin')->end()
                                        ->scalarNode('action')->end()
                                        ->scalarNode('label')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();


        return $treeBuilder;
    }
}
