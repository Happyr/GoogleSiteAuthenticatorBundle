<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Model;

/**
 * This is the access token model that will be saved in the cache storage.
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
     * @var \DateTime createdAt
     */
    protected $createdAt;

    /**
     * @var \DateTime updatedAt
     */
    protected $updatedAt;

    /**
     * @param string $name
     * @param string $token
     */
    public function __construct($name, $token)
    {
        $this->name = $name;
        $this->token = $token;
        $this->initTimestamps();
    }

    public function __toString()
    {
        return $this->getToken();
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

    /**
     * @return \DateTime
     */
    public function getExpires()
    {
        $data = json_decode($this->getToken(), true);
        $exp = new \DateTime();
        $exp->setTimestamp($data['created'])
            ->modify('+'.$data['expires_in'].'seconds');

        return $exp;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        $this->updateTimestamp();

        return $this;
    }

    protected function updateTimestamp()
    {
        $this->updatedAt = new \DateTime();
    }

    protected function initTimestamps()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
}
