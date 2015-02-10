<?php

namespace BlueBear\GameBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('blue_bear_game');

        $rootNode
            ->children()
                ->arrayNode('entity_type')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                        ->prototype('array')
                        ->children()
                            ->arrayNode('attributes')
                                ->prototype('scalar')->end()
                            ->end()
                            ->scalarNode('label')->isRequired()->end()
                        ->end()
                    ->end()
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
            ->end();


        return $treeBuilder;
    }
}
