<?php

namespace Happyr\GoogleSiteAuthenticatorBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Tobias Nyholm
 */
class AdminController extends Controller
{
    const SESSION_KEY = 'google_token_name';

    /**
     * @param Request $request
     *
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        /** @var \Google_Client $client */
        $clientProvider = $this->get('happyr.google_site_authenticator.client_provider');
        $tokenConfig = $this->get('happyr.google_site_authenticator.token_config');
        $tokenNames = $tokenConfig->getAllKeys();

        $tokens = array();
        foreach ($tokenNames as $tokenName) {
            $tokens[$tokenName] = $clientProvider->getAccessToken($tokenName);
        }

        return array(
            'tokens'=>$tokens,
        );
    }

    /**
     * This action starts the authentication
     *
     * @param Request $request
     * @param $name
     *
     * @return Response
     */
    public function authenticateAction(Request $request, $name)
    {
        /** @var \Google_Client $client */
        $clientProvider = $this->get('happyr.google_site_authenticator.client_provider');
        $client = $clientProvider->getClient($name);

        $request->getSession()->set(self::SESSION_KEY, $name);

        return $this->redirect($client->createAuthUrl());
    }

    /**
     * This action is used when the user has authenticated with google
     *
     * @param Request $request
     *
     * @return Response
     */
    public function returnAction(Request $request)
    {
        $name=$request->getSession()->get(self::SESSION_KEY, null);

        /** @var \Google_Client $client */
        $clientProvider = $this->get('happyr.google_site_authenticator.client_provider');
        $client = $clientProvider->getClient($name);

        if ($request->query->has('code')) {
            try {
                $client->authenticate($request->query->get('code'));
                $message = 'Authenticated!';
            } catch (\Google_Auth_Exception $e) {
                $message = $e->getMessage();
            }
        } else {
            $message = 'Authentication aborted';
        }

        $this->get('session')->getFlashbag()->add('msg', $message);

        return $this->redirect($this->generateUrl('happyr.google_site_authenticator.index'));
    }
} 