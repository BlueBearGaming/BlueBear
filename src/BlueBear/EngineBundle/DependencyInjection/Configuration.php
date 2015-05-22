<?php

namespace BlueBear\EngineBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('blue_bear_engine');

        $rootNode
            ->children()
                ->arrayNode('events')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('request')->isRequired()->end()
                            ->scalarNode('response')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('game')
                    ->children()
                        ->arrayNode('entity_type')
                            ->prototype('array')
                                ->children()
                                    ->arrayNode('attributes')
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->scalarNode('label')->isRequired()->end()
                                    ->scalarNode('description')->end()
                                    ->scalarNode('parent')->end()
                                    ->arrayNode('behaviors')
                                        ->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('behaviors')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('attribute')
                            ->isRequired()
                            ->requiresAtLeastOneElement()
                            ->prototype('array')
                            ->children()
                                ->scalarNode('label')->isRequired()->end()
                                ->scalarNode('type')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
