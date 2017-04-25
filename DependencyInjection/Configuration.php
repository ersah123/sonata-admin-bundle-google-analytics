<?php

namespace Ersah\GABundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ersah_ga');

        $rootNode
            ->children()
                ->scalarNode('google_analytics_json_key')->end()
                ->scalarNode('view_id')->end()
                ->scalarNode('limit')->end()
                ->scalarNode('from')->end()
                ->scalarNode('to')->end()

                ->arrayNode('list')
                    ->children()
                        ->arrayNode('dimensions')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('value')->end()
                                    ->scalarNode('label')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('metrics')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('value')->end()
                                    ->scalarNode('label')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('main')
                    ->children()
                        ->arrayNode('metrics')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('value')->end()
                                    ->scalarNode('label')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('sortBy')->end()
                ->scalarNode('sorting')->end()
            ->end();

        return $treeBuilder;
    }
}
