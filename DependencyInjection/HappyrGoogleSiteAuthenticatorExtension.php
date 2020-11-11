<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\DependencyInjection;

use Happyr\GoogleSiteAuthenticatorBundle\Model\TokenConfig;
use Happyr\GoogleSiteAuthenticatorBundle\Service\ClientProvider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class HappyrGoogleSiteAuthenticatorExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // Configure the correct PSR-6 cache
        $clientProvider = $container->getDefinition(ClientProvider::class);
        $clientProvider->replaceArgument(1, new Reference($config['cache_service']));

        // Configure TokenConfig
        $definition = $container->getDefinition(TokenConfig::class);
        $definition->replaceArgument(0, $config['tokens']);

        // make sure we shortcut the service name
        $decorator = new ChildDefinition('happyr.google_site_authenticator.client');
        foreach ($config['tokens'] as $name => $tokenConfig) {
            $service = $container->setDefinition('google.client.'.$name, $decorator);
            $service->replaceArgument(0, $name);
        }

        // set an alias for happyr.google_site_authenticator.client
        $container->setAlias('google.client', 'happyr.google_site_authenticator.client');
    }
}
