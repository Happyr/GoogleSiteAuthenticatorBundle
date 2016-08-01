<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseTestCase extends WebTestCase
{
    protected static function getKernelClass()
    {
        require_once __DIR__.'/app/AppKernel.php';

        return 'Happyr\GoogleSiteAuthenticatorBundle\Tests\Functional\app\AppKernel';
    }

    protected static function createKernel(array $options = array())
    {
        $class = self::getKernelClass();

        return new $class(
            isset($options['config']) ? $options['config'] : 'default.yml'
        );
    }
}
