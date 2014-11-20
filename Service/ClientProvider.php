<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Service;

use Doctrine\Common\Cache\CacheProvider;
use Happyr\GoogleSiteAuthenticatorBundle\Model\AccessToken;
use Happyr\GoogleSiteAuthenticatorBundle\Model\TokenConfig;
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
     * @var CacheProvider storage
     */
    private $storage;

    /**
     * @param TokenConfig $config
     * @param $storage
     */
    public function __construct(TokenConfig $config, CacheProvider $storage)
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
        $name = $this->config->getKey($tokenName);

        if ($accessToken===null) {
            $this->storage->delete($name);
            return;
        }

        $this->storage->save($name, new AccessToken($name, $accessToken));
    }

    /**
     * @param null $tokenName
     *
     * @return AccessToken
     */
    public function getAccessToken($tokenName = null)
    {
        $accessToken = $this->storage->fetch($this->config->getKey($tokenName));

        if (empty($accessToken)) {
            return null;
        }

        return $accessToken;
    }

    /**
     * @param string|null $tokenName
     *
     * @return \Google_Client
     */
    public function getClient($tokenName = null)
    {
        $client = new \Google_Client();

        $client->setApplicationName($this->config->getApplicationName());
        $client->setClientId($this->config->getClientId($tokenName));
        $client->setClientSecret($this->config->getSecret($tokenName));
        $client->setRedirectUri($this->config->getRedirectUrl($tokenName));
        $client->setScopes($this->config->getScopes($tokenName));

        // This will allow us to refresh the token
        $client->setAccessType('offline');

        if (!empty($tokenName)) {
            $accessToken = $this->getAccessToken($tokenName);

            if ($accessToken) {
                $client->setAccessToken((string) $accessToken);

                $this->refreshToken($client);
            }
        }

        return $client;
    }

    /**
     * Get the refresh token
     *
     * @param \Google_Client $client
     */
    private function refreshToken(\Google_Client $client)
    {
        $accessToken = $client->getAccessToken();
        $data = json_decode($accessToken, true);

        if (isset($data['refresh_token'])) {
            $client->refreshToken($data['refresh_token']);
            return true;
        }

        return false;
    }
}