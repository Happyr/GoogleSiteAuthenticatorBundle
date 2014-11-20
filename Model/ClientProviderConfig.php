<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Model;

use Happyr\GoogleSiteAuthenticatorBundle\Storage\StorageInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @author  Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ClientProviderConfig
{
    const APPLICATION_NAME = 'GoogleAuthenticator';

    /**
     * @var string apiSecret
     */
    private $secret;

    /**
     * @var string apiClientId
     */
    private $clientId;

    /**
     * @var string redirectUrl
     */
    private $redirectUrl;

    /**
     * @var array scopes
     */
    private $scopes;

    /**
     * @param $clientId
     * @param $secret
     * @param $redirectUrl
     * @param $scopes
     */
    public function __construct($clientId, $secret, $redirectUrl, $scopes)
    {
        $this->clientId = $clientId;
        $this->redirectUrl = $redirectUrl;
        $this->scopes = $scopes;
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @return array
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @return string
     */
    public function getApplicationName()
    {
        return self::APPLICATION_NAME;
    }
}