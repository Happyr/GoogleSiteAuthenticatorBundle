<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root=$treeBuilder->root('happyr_google_site_authenticator');

        $root->children()
            ->enumNode('storage')->defaultValue('cache')
                ->values(array('file', 'cache'))
            ->end()
            ->append($this->getTokenNode())
        ->end();


        return $treeBuilder;
    }

    /**
     *
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function getTokenNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('tokens');
        $node
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
            ->children()
                ->scalarNode('client_id')->isRequired()->end()
                ->scalarNode('client_secret')->isRequired()->end()
                ->scalarNode('redirect_url')->isRequired()->end()
                ->variableNode('scopes')->end()
            ->end()
        ->end();

        return $node;
    }
}
