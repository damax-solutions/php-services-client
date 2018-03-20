<?php

declare(strict_types=1);

namespace Damax\Services\Client\Bridge\Symfony\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('damax_services_client');
        $rootNode
            ->children()
                ->scalarNode('api_key')->isRequired()->end()
                ->scalarNode('endpoint')
                    ->cannotBeEmpty()
                    ->defaultValue('https://product.damax.solutions/api')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
