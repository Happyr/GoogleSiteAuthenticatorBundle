<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Service;

use Happyr\GoogleSiteAuthenticatorBundle\Model\AccessToken;
use Happyr\GoogleSiteAuthenticatorBundle\Model\TokenConfig;
use Happyr\GoogleSiteAuthenticatorBundle\Storage\StorageInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ClientProvider
{
    /**
     * @var \Happyr\GoogleSiteAuthenticatorBundle\Model\TokenConfig config
     */
    private $config;

    /**
     * @var storage
     */
    private $storage;

    /**
     * @param TokenConfig $config
     * @param $storage
     */
    public function __construct(TokenConfig $config, $storage)
    {
        $this->config = $config;
        $this->storage = $storage;
    }

    /**
     * @param string $accessToken
     * @param string|null $tokenName
     */
    public function setAccessToken($accessToken, $tokenName = null)
    {
        $this->storage->store(new AccessToken($this->config->getKey($tokenName), $accessToken));
    }

    /**
     * @param null $tokenName
     *
     * @return AccessToken
     */
    public function getAccessToken($tokenName = null)
    {
        return $this->storage->fetch($this->config->getKey($tokenName));
    }

    /**
     * @param string|null $tokenName
     *
     * @return \Google_Client
     */
    public function getClient($tokenName = null)
    {
        $client = new \Google_Client();

        if (!empty($tokenName)) {
            $accessToken = $this->getAccessToken($tokenName);

            if ($accessToken) {
                $client->setAccessToken((string) $accessToken);

                //TODO maybe call refresh token

                return $client;
            }
        }

        $client->setApplicationName($this->config->getApplicationName());
        $client->setClientId($this->config->getClientId($tokenName));
        $client->setClientSecret($this->config->getSecret($tokenName));
        $client->setRedirectUri($this->config->getRedirectUrl($tokenName));
        $client->setScopes($this->config->getScopes($tokenName));

        return $client;
    }

    public function refreshToken(\Google_Client $client)
    {
        //TODO refresh
    }
}