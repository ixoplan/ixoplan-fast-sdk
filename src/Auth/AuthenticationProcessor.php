<?php

namespace Ixolit\Dislo\CDE\Auth;

use Ixolit\Dislo\CDE\Exceptions\CookieNotSetException;
use Ixolit\Dislo\CDE\Interfaces\RequestAPI;
use Ixolit\Dislo\CDE\Interfaces\ResponseAPI;
use Ixolit\Dislo\Client;
use Ixolit\Dislo\Exceptions\InvalidTokenException;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;

class AuthenticationProcessor {
	/**
	 * @var RequestAPI
	 */
	private $requestApi;

	/**
	 * @var ResponseAPI
	 */
	private $responseApi;

	/**
	 * @param RequestAPI  $requestApi
	 * @param ResponseAPI $responseApi
	 */
	public function __construct(RequestAPI $requestApi, ResponseAPI $responseApi) {
		$this->requestApi  = $requestApi;
		$this->responseApi = $responseApi;
	}

	public function authenticate($uniqueUserField, $password, $tokenTimeout = 2592000) {
		$apiClient = new Client();
		$authenticationResponse = $apiClient->userAuthenticate(
			$uniqueUserField,
			$password,
			$this->requestApi->getRemoteAddress()->__toString(),
			$tokenTimeout
		);
		$this->responseApi->setCookie('auth-token', $authenticationResponse->getAuthToken());
	}

	public function deauthenticate() {
		$authToken = $this->requestApi->getCookie('auth-token');
		if ($authToken) {
			$apiClient = new Client();
			try {
				$apiClient->userDeauthenticate($authToken);
			} catch (ObjectNotFoundException $e) {
			}
		}
	}

	/**
	 * Checks and extends a token.
	 *
	 * @return string
	 *
	 * @throws AuthenticationRequiredException
	 */
	public function extendToken() {
		try {
			$authToken = $this->requestApi->getCookie('auth-token')->getValue();
		} catch (CookieNotSetException $e) {
			throw new AuthenticationRequiredException();
		}
		$apiClient = new Client();
		try {
			$apiClient->userUpdateToken($authToken, 'x', $this->requestApi->getRemoteAddress());
			return $authToken;
		} catch (ObjectNotFoundException $e) {
			throw new AuthenticationRequiredException();
		} catch (InvalidTokenException $e) {
			throw new AuthenticationRequiredException();
		}
	}
}