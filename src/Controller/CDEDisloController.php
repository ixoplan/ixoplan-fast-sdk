<?php

namespace Ixolit\Dislo\CDE\Controller;


use Ixolit\CDE\Controller\CDEController;
use Ixolit\CDE\Interfaces\FormProcessorInterface;
use Ixolit\CDE\Interfaces\RequestAPI;
use Ixolit\CDE\Interfaces\ResponseAPI;
use Ixolit\Dislo\CDE\Auth\AuthenticationProcessor;
use Ixolit\Dislo\CDE\Auth\AuthenticationRequiredException;
use Ixolit\Dislo\Client;
use Ixolit\Dislo\Context\UserContext;
use Ixolit\Dislo\WorkingObjects\User;

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

    /** @var UserContext */
    private $authenticatedUserContext;

    /**
     * CDEDisloController constructor.
     *
     * @param Client                      $client
     * @param FormProcessorInterface|null $formProcessor
     * @param RequestAPI|null             $requestApi
     * @param ResponseAPI|null            $responseApi
     */
    public function __construct(Client $client,
                                FormProcessorInterface $formProcessor = null,
                                RequestAPI $requestApi = null,
                                ResponseAPI $responseApi = null
    ) {
        parent::__construct($formProcessor, $requestApi, $responseApi);

        $this->client = $client;
    }

    /**
     * @return Client
     */
    protected function getClient() {
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

    /**
     * @return UserContext
     */
    protected function getUserContext() {
        if (!isset($this->authenticatedUserContext)) {
            try {
                $this->authenticatedUserContext = new UserContext(
                    $this->getClient(),
                    $this->getAuthenticationProcessor()->getAuthenticatedUser()
                );
            } catch (AuthenticationRequiredException $e) {}
        }

        return $this->authenticatedUserContext;
    }

    /**
     * @return User
     */
    protected function getUser() {
        return $this->getUserContext() ? $this->getUserContext()->getUser() : null;
    }

}