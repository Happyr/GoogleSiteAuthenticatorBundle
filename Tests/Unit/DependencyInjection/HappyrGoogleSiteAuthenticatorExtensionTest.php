<?php

namespace Tests\Unit\DependencyInjection;

use Happyr\GoogleSiteAuthenticatorBundle\DependencyInjection\HappyrGoogleSiteAuthenticatorExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class HappyrGoogleSiteAuthenticatorExtensionTest extends AbstractExtensionTestCase
{
    protected function getMinimalConfiguration()
    {
        return [
            'cache_service' => 'cache',
            'tokens' => [
                'google_drive' => [
                    'client_id' => '00000000000-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com',
                    'client_secret' => 'xxxxx-xxxxx_xxxxxxxxxxxx',
                    'redirect_url' => 'http://www.domain.com/admin/authenticate-google/return-url',
                    'scopes' => ['https://www.googleapis.com/auth/drive'],
                ],
            ],
        ];
    }

    protected function getContainerExtensions()
    {
        return [
            new HappyrGoogleSiteAuthenticatorExtension(),
        ];
    }

    public function testTracker()
    {
        $this->load();

        $this->assertContainerBuilderHasService('happyr.google.client_provider');
        $this->assertContainerBuilderHasService('google.client.google_drive');
        $this->assertContainerBuilderHasService('google.client');
    }
}
