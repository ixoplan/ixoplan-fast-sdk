<?php

// TODO: dislo

namespace Ixolit\Dislo\CDE\Auth;

use Ixolit\Dislo\CDE\CDECookieCache;
use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\CDE\Exceptions\CookieNotSetException;
use Ixolit\Dislo\CDE\Interfaces\RequestAPI;
use Ixolit\Dislo\CDE\Interfaces\ResponseAPI;
use Ixolit\Dislo\Exceptions\AuthenticationException;
use Ixolit\Dislo\Exceptions\AuthenticationInvalidCredentialsException;
use Ixolit\Dislo\Exceptions\AuthenticationRateLimitedException;
use Ixolit\Dislo\Exceptions\InvalidTokenException;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;

class AuthenticationProcessor {

	const COOKIE_NAME_AUTH_TOKEN = 'auth-token';

	/**
	 * @var RequestAPI
	 */
	private $requestApi;

	/**
	 * @var ResponseAPI
	 */
	private $responseApi;
	/**
	 * @var int
	 */
	private $tokenTimeout;

	/**
	 * @param RequestAPI  $requestApi
	 * @param ResponseAPI $responseApi
	 * @param int         $tokenTimeout
	 */
	public function __construct(RequestAPI $requestApi, ResponseAPI $responseApi, $tokenTimeout = 2592000) {
		$this->requestApi  = $requestApi;
		$this->responseApi = $responseApi;
		$this->tokenTimeout = $tokenTimeout;
	}

	/**
	 * Authenticate a user. If successful, the authentication token is set into a cookie, and also returned.
	 *
	 * @param string $uniqueUserField
	 * @param string $password
	 *
	 * @return string
	 *
	 * @throws AuthenticationRateLimitedException
	 * @throws AuthenticationInvalidCredentialsException
	 * @throws AuthenticationException
	 */
	public function authenticate($uniqueUserField, $password) {
		$apiClient = new CDEDisloClient();
		$authenticationResponse = $apiClient->userAuthenticate(
			$uniqueUserField,
			$password,
			$this->requestApi->getRemoteAddress()->__toString(),
			$this->tokenTimeout,
			'{}'
		);
		CDECookieCache::getInstance()->write(self::COOKIE_NAME_AUTH_TOKEN, $authenticationResponse->getAuthToken());
		return $authenticationResponse->getAuthToken();
	}

	/**
	 * Invalidate the current authentication token.
	 */
	public function deauthenticate() {
		try {
			$authToken = $this->extendToken();
			$apiClient = new CDEDisloClient();
			try {
				$apiClient->userDeauthenticate($authToken);

				//delete auth cookie
				CDECookieCache::getInstance()->delete(self::COOKIE_NAME_AUTH_TOKEN);
			} catch (ObjectNotFoundException $e) {
			}
		} catch (AuthenticationRequiredException $e) {
		}
	}

	/**
	 * Checks and extends a token from cookie.
	 *
	 * @param null $authToken
	 *
	 * @return string
	 *
	 * @throws AuthenticationRequiredException
	 */
	public function extendToken($authToken = null) {
		if (!$authToken) {
			try {
				$authToken = CDECookieCache::getInstance()->read(self::COOKIE_NAME_AUTH_TOKEN);
				if (empty($authToken)) {
					CDECookieCache::getInstance()->delete(self::COOKIE_NAME_AUTH_TOKEN);
					throw new AuthenticationRequiredException();
				}
			} catch (CookieNotSetException $e) {
				throw new AuthenticationRequiredException();
			}
		}
		$apiClient = new CDEDisloClient();
		try {
			$extendResponse = $apiClient->userUpdateToken($authToken, '{}', $this->requestApi->getRemoteAddress());
			CDECookieCache::getInstance()->write(
				self::COOKIE_NAME_AUTH_TOKEN,
				$extendResponse->getAuthToken()->getToken(),
				$this->tokenTimeout
			);
			return $authToken;
		} catch (ObjectNotFoundException $e) {
			throw new AuthenticationRequiredException();
		} catch (InvalidTokenException $e) {
			throw new AuthenticationRequiredException();
		}
	}
}
