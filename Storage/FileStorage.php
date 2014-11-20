<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Storage;

use Happyr\GoogleSiteAuthenticatorBundle\Model\AccessToken;

/**
 * @author Tobias Nyholm
 */
class FileStorage implements StorageInterface
{
    /**
     * @param AccessToken $at
     */
    public function store(AccessToken $at)
    {

    }

    /**
     * @param $tokenName
     *
     * @return AccessToken
     */
    public function fetch($tokenName)
    {

    }
}