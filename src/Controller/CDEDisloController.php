<?php

namespace Ixolit\Dislo\CDE\Controller;


use Ixolit\CDE\Controller\CDEController;
use Ixolit\Dislo\CDE\Auth\AuthenticationProcessor;
use Ixolit\Dislo\CDE\Auth\AuthenticationRequiredException;
use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\Client;

/**
 * Class CDEDisloController
 *
 * @package Ixolit\Dislo\CDE\Controller
 */
class CDEDisloController extends CDEController {

    /** @var Client */
    private $client;

    /** @var AuthenticationProcessor */
    private $authenticationProcessor;

    /** @var string */
    private $authToken;

    /**
     * @return Client
     */
    protected function getClient() {
        if (!isset($this->client)) {
            //default client
            $this->client = new CDEDisloClient();
        }

        return $this->client;
    }

    /**
     * @return AuthenticationProcessor
     */
    protected function getAuthenticationProcessor() {
        if (!isset($this->authenticationProcessor)) {
            //default authentication processor
            $this->authenticationProcessor = new AuthenticationProcessor(
                $this->getRequestApi(),
                $this->getResponseApi()
            );
        }

        return $this->authenticationProcessor;
    }

    /**
     * @return string
     *
     * @throws AuthenticationRequiredException
     */
    protected function getAndExtendAuthToken() {
        if (!isset($this->authToken)) {
            $this->authToken = $this->getAuthenticationProcessor()->extendToken();
        }

        return $this->authToken;
    }

}