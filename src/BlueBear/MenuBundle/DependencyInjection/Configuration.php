<?php

namespace BlueBear\MenuBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('blue_bear_menu');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->arrayNode('menus')
                    ->prototype('array')
                        ->children()
                            ->enumNode('type')
                                ->values(['static', 'dynamic'])
                                ->defaultValue('static')
                            ->end()
                            ->arrayNode('main_item')
                                ->children()
                                    ->scalarNode('route')->end()
                                    ->scalarNode('title')->end()
                                ->end()
                            ->end()
                            ->scalarNode('template')->end()
                            ->arrayNode('items')
                                ->prototype('array')
                                    ->children()
                                    ->scalarNode('route')->end()
                                    ->scalarNode('title')->end()
                                    ->scalarNode('admin')->end()
                                    ->scalarNode('action')->end()
                                    ->arrayNode('permissions')
                                        ->defaultValue(['ROLE_USER'])
                                        ->prototype('scalar')
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