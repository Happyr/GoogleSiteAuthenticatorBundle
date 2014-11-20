<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Model;

/**
 * @author Tobias Nyholm
 */
class AccessToken
{
    /**
     * @var string name
     */
    protected $name;

    /**
     * @var string token
     */
    protected $token;

    /**
     * @param string $name
     * @param string $token
     */
    public function __construct($name, $token)
    {
        $this->name = $name;
        $this->token = $token;
    }

    public function __toString()
    {
        $this->getToken();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}