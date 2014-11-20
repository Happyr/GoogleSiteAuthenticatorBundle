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
     * @param $id
     *
     * @return AccessToken
     */
    public function fetch($id)
    {

    }
}