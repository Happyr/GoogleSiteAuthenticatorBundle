<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Service;

use Happyr\GoogleSiteAuthenticatorBundle\Model\AccessToken;
use Happyr\GoogleSiteAuthenticatorBundle\Model\TokenConfig;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ClientProvider
{
    /**
     * @var TokenConfig config
     */
    private $config;

    /**
     * @var CacheItemPoolInterface storage
     */
    private $pool;

    public function __construct(TokenConfig $config, CacheItemPoolInterface $pool)
    {
        $this->config = $config;
        $this->pool = $pool;
    }

    public function getClient(string $tokenName = null): \Google_Client
    {
        $client = new \Google_Client();

        // Set values from configuration
        $client->setApplicationName($this->config->getApplicationName());
        $client->setClientId($this->config->getClientId($tokenName));
        $client->setClientSecret($this->config->getSecret($tokenName));
        $client->setRedirectUri($this->config->getRedirectUrl($tokenName));
        $client->setScopes($this->config->getScopes($tokenName));

        if (null !== $accessToken = $this->getAccessToken($tokenName)) {
            // set access token to client if we got one
            $client->setAccessToken((string) $accessToken);

            // make sure to refresh the stored access token
            $this->refreshToken($client);
        }

        return $client;
    }

    /**
     * Check if a token is valid.
     * This is an expensive operation that makes multiple API calls.
     */
    public function isTokenValid(string $tokenName = null): bool
    {
        // We must fetch the client here. A client will automatically refresh the stored access token.
        $client = $this->getClient($tokenName);
        if (null === $accessToken = $client->getAccessToken()) {
            return false;
        }

        // Get the token string from access token
        $token = \json_decode($accessToken)->access_token;
        $url = \sprintf('https://www.google.com/accounts/AuthSubTokenInfo?bearer_token=%s', $token);
        if (false === @\file_get_contents($url)) {
            return false;
        }

        // Retrieve HTTP status code
        list($version, $statusCode, $msg) = \explode(' ', $http_response_header[0], 3);

        return 200 === (int) $statusCode;
    }

    /**
     * Store the access token in the storage.
     */
    public function setAccessToken(?string $accessToken, string $tokenName = null)
    {
        $name = $this->config->getKey($tokenName);
        $cacheKey = $this->creteCacheKey($name);

        if (null === $accessToken) {
            $this->pool->deleteItem($cacheKey);

            return;
        }

        $item = $this->pool->getItem($cacheKey)->set(new AccessToken($name, $accessToken));
        $this->pool->save($item);
    }

    /**
     * Get access token from storage.
     */
    protected function getAccessToken(string $tokenName = null): ?AccessToken
    {
        $cacheKey = $this->creteCacheKey($this->config->getKey($tokenName));
        $item = $this->pool->getItem($cacheKey);

        if (!$item->isHit()) {
            return null;
        }

        return $item->get();
    }

    /**
     * If we got a refresh token, use it to retrieve a good access token.
     */
    private function refreshToken(\Google_Client $client): bool
    {
        $accessToken = $client->getAccessToken();
        $data = \json_decode($accessToken, true);

        try {
            if (isset($data['refresh_token'])) {
                $client->refreshToken($data['refresh_token']);

                return true;
            }
        } catch (\Google_Auth_Exception $e) {
        }

        return false;
    }

    private function creteCacheKey(string $name): string
    {
        return \sha1('happyr_google-site-authenticator_'.$name);
    }
}
