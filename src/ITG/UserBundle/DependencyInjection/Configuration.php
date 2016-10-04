<?php

namespace ITG\UserBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('itg_user');

        $rootNode
            ->children()
            ->arrayNode('roles')->prototype('scalar')->end()->end()
            ->arrayNode('registration')->canBeEnabled()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
