<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Model;

/**
 * This class holds all the tokens' configuration.
 */
class TokenConfig
{
    const APPLICATION_NAME = 'GoogleAuthenticator';

    private $tokens;

    /**
     * @var string defaultKey
     */
    private $defaultKey;

    /**
     * @param array $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;

        if (isset($tokens['default'])) {
            $this->defaultKey = 'default';
        } else {
            // take the first key
            reset($tokens);
            $this->defaultKey = key($tokens);
        }
    }

    /**
     * Get the key from the argument or the default key.
     */
    public function getKey(string $key = null): string
    {
        if ($key === null) {
            return $this->defaultKey;
        }

        if (!isset($this->tokens[$key])) {
            throw new \LogicException(sprintf('Token with name %s could not be found', $key));
        }

        return $key;
    }

    /**
     * Get all keys.
     *
     * @return array
     */
    public function getAllKeys(): array
    {
        return array_keys($this->tokens);
    }

    /**
     * @return string
     */
    public function getClientId(string $tokenName = null)
    {
        return $this->tokens[$this->getKey($tokenName)]['client_id'];
    }

    /**
     * @return string
     */
    public function getRedirectUrl($tokenName = null)
    {
        return $this->tokens[$this->getKey($tokenName)]['redirect_url'];
    }

    /**
     * @return array
     */
    public function getScopes($tokenName = null)
    {
        return $this->tokens[$this->getKey($tokenName)]['scopes'];
    }

    /**
     * @return string
     */
    public function getSecret($tokenName = null)
    {
        return $this->tokens[$this->getKey($tokenName)]['client_secret'];
    }

    public function getApplicationName(): string
    {
        return self::APPLICATION_NAME;
    }
}
