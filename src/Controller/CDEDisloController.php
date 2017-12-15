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
use Ixolit\Dislo\Exceptions\InvalidTokenException;
use Ixolit\Dislo\WorkingObjects\CachedObject;
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

    /** @var CachedObject */
    private $authenticatedUserContextCachedObject;

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
        return $this->getAuthenticationProcessor()->extendToken();
    }

    /**
     * @return UserContext
     *
     * @throws InvalidTokenException
     * @throws AuthenticationRequiredException
     */
    protected function getUserContext() {
        if (!isset($this->authenticatedUserContextCachedObject)) {
            $this->authenticatedUserContextCachedObject = new CachedObject($this->createNewUserContext());
        }

        if (empty($this->authenticatedUserContextCachedObject->getObject())) {
            throw new AuthenticationRequiredException();
        }

        return $this->authenticatedUserContextCachedObject->getObject();
    }

    /**
     * @param UserContext $userContext
     *
     * @return $this
     */
    protected function setUserContext(UserContext $userContext) {
        $this->authenticatedUserContextCachedObject = new CachedObject($userContext);

        return $this;
    }

    /**
     * @return UserContext|null
     */
    protected function createNewUserContext() {
        try {
            return new UserContext(
                $this->getClient(),
                $this->getAuthenticationProcessor()->getAuthenticatedUser()
            );
        } catch (AuthenticationRequiredException $e) {
        } catch (InvalidTokenException $e) {}

        return null;
    }

    /**
     * @return User
     */
    protected function getUser() {
        return $this->getUserContext()->getUser();
    }

}