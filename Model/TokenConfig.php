<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Model;

use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * This class holds all the tokens' configuration
 */
class TokenConfig
{
    const APPLICATION_NAME = 'GoogleAuthenticator';

    /**
     * @var array tokens
     */
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
     * Get the key from the argument or the default key
     *
     * @param null $key
     *
     * @return string
     */
    public function getKey($key=null)
    {
        if ($key==null) {
            return $this->defaultKey;
        }

        if (!isset($this->tokens[$key])) {
            throw new \LogicException(sprintf('Token with name %s could not be found', $key));
        }

        return $key;
    }

    /**
     * Get all keys
     *
     * @return array
     */
    public function getAllKeys()
    {
        return array_keys($this->tokens);
    }

    /**
     * @return string
     */
    public function getClientId($tokenName=null)
    {
        return $this->tokens[$this->getKey($tokenName)]['client_id'];
    }

    /**
     * @return string
     */
    public function getRedirectUrl($tokenName=null)
    {
        return $this->tokens[$this->getKey($tokenName)]['redirect_url'];
    }

    /**
     * @return array
     */
    public function getScopes($tokenName=null)
    {
        return $this->tokens[$this->getKey($tokenName)]['scopes'];
    }

    /**
     * @return string
     */
    public function getSecret($tokenName=null)
    {
        return $this->tokens[$this->getKey($tokenName)]['client_secret'];
    }

    /**
     * @return string
     */
    public function getApplicationName()
    {
        return self::APPLICATION_NAME;
    }
}