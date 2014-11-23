<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class HappyrGoogleSiteAuthenticatorExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config=$this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('storage.yml');

        // Configure the correct storage
        $storage=new Reference($config['cache_service']);
        $clientProvider=$container->getDefinition('happyr.google_site_authenticator.client_provider');
        $clientProvider->replaceArgument(1, $storage);

        // Configure ClientProviderConfig
        $definition = $container->getDefinition('happyr.google_site_authenticator.token_config');
        $definition->replaceArgument(0, $config['tokens']);

        // make sure we shortcut the service name
        $decorator=new DefinitionDecorator('happyr.google_site_authenticator.client');
        foreach ($config['tokens'] as $name=>$tokenConfig) {
            $service=$container->setDefinition('google.client.'.$name, $decorator);
            $service->replaceArgument(0, $name);
        }

        // set an alias for happyr.google_site_authenticator.client
        $container->setAlias('google.client', 'happyr.google_site_authenticator.client');
    }
}
