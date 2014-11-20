<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Model;

/**
 * @author Tobias Nyholm
 */
class AccessToken
{
    /**
     * @var string id
     */
    protected $id;

    /**
     * @var string token
     */
    protected $token;

    /**
     * @param string $id
     * @param string $token
     */
    public function __construct($id, $token)
    {
        $this->id = $id;
        $this->token = $token;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}