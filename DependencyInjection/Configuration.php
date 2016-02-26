<?php

namespace Webburza\Sylius\GoogleEcommerceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('webburza_sylius_google_ecommerce');

        $rootNode
            ->children()
                ->scalarNode('key')
                ->isRequired()
                ->cannotBeEmpty()
            ->end();

        return $treeBuilder;
    }
}
