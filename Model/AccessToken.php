<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Model;

/**
 * This is the access token model that will be saved in the cache storage.
 */
class AccessToken
{
    private $name;
    private $token;
    private $createdAt;
    private $updatedAt;

    public function __construct(string $name, string $token)
    {
        $this->name = $name;
        $this->token = $token;
        $this->initTimestamps();
    }

    public function __toString()
    {
        return $this->getToken();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpires(): \DateTimeInterface
    {
        $data = \json_decode($this->getToken(), true);
        $exp = new \DateTimeImmutable();

        return $exp->setTimestamp($data['created'])
            ->modify('+'.$data['expires_in'].'seconds');
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        $this->updateTimestamp();

        return $this;
    }

    protected function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTime();
    }

    protected function initTimestamps(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
}
