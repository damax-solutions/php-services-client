<?php

declare(strict_types=1);

namespace Damax\Services\Client\Bridge\Symfony\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('damax_services_client');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('api_key')->isRequired()->end()
                ->scalarNode('endpoint')
                    ->cannotBeEmpty()
                    ->defaultValue('https://api.damax.solutions/services')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
