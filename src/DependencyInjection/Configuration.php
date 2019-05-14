<?php

namespace App\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder();
        $root = $builder->root('bluebear');

        $root
            ->children()
            ->arrayNode('engine')
                ->children()
                ->arrayNode('behaviors')
                    ->prototype('variable')
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
