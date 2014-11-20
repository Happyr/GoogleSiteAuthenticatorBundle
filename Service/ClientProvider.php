<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Service;

use Happyr\GoogleSiteAuthenticatorBundle\Model\ClientProviderConfig;
use Happyr\GoogleSiteAuthenticatorBundle\Storage\StorageInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ClientProvider
{
    /**
     * @var \Happyr\GoogleSiteAuthenticatorBundle\Model\ClientProviderConfig config
     */
    private $config;

    /**
     * @var StorageInterface storage
     */
    private $storage;

    /**
     * @param ClientProviderConfig $config
     * @param StorageInterface $storage
     */
    public function __construct(ClientProviderConfig $config, StorageInterface $storage)
    {
        $this->config = $config;
        $this->storage = $storage;
    }

    /**
     * @param string|null $accessTokenId
     *
     * @return \Google_Client
     */
    public function getClient($accessTokenId = null)
    {
        $client = new \Google_Client();

        if (!empty($accessTokenId)) {
            $accessToken = $this->storage->fetch($accessTokenId);

            if ($accessToken) {
                $client->setAccessToken($accessToken);

                //TODO maybe call refresh token

                return $client;
            }
        }

        $client->setApplicationName(self::APPLICATION_NAME);
        $client->setClientId($this->apiClientId);
        $client->setClientSecret($this->apiSecret);
        $client->setRedirectUri($this->redirectUrl);
        $client->setScopes($this->scopes);

        return $client;
    }

    public function refreshToken(\Google_Client $client)
    {
        //TODO refresh
    }
}