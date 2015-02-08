<?php

namespace BlueBear\ActionBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('blue_bear_action');

        $rootNode
            ->prototype('array') // Action configurations
                ->children()
                    ->scalarNode('class')->end()
                    ->scalarNode('route')->end()
                    ->arrayNode('permissions')
                        ->prototype('scalar')->end()
                    ->end()
                    ->arrayNode('actions')
                        ->useAttributeAsKey('name')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('icon')->defaultValue('icon-question')->end()
                                ->booleanNode('require_user')->defaultValue(false)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
