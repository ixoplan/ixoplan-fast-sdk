<?php

namespace Ixolit\Dislo\CDE\Auth;

use Ixolit\CDE\CDECookieCache;
use Ixolit\CDE\Exceptions\CookieNotSetException;
use Ixolit\CDE\Interfaces\RequestAPI;
use Ixolit\CDE\Interfaces\ResponseAPI;
use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\Exceptions\AuthenticationException;
use Ixolit\Dislo\Exceptions\AuthenticationInvalidCredentialsException;
use Ixolit\Dislo\Exceptions\AuthenticationRateLimitedException;
use Ixolit\Dislo\Exceptions\InvalidTokenException;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;

class AuthenticationProcessor {

	const COOKIE_NAME_AUTH_TOKEN = 'auth-token';

	const KEY_VOLATILE = 'volatile';

	const TOKEN_TIMEOUT_VOLATILE =  1 * 24*60*60;   //  1 day
	const TOKEN_TIMEOUT_LONGTERM = 90 * 24*60*60;   // 90 days, dislo maximum

	/**
	 * @var RequestAPI
	 */
	private $requestApi;

	/**
	 * @var ResponseAPI
	 */
	private $responseApi;

	/**
	 * @var string
	 */
	private $cookieName;

	/**
	 * @var int
	 */
	private $tokenTimeoutLongterm;

	/**
	 * @var int
	 */
	private $tokenTimeoutVolatile;

	/**
	 * Helper for user authentication and verification. Stores the authentication token in a cookie.
	 * The default mode uses the maximum token lifetime and renews the cookie frequently ("stay logged in").
	 * The alternative volatile mode uses a short token lifetime and a session cookie.
	 *
	 * @param RequestAPI  $requestApi
	 * @param ResponseAPI $responseApi
	 * @param int         $tokenTimeoutLongterm
	 * @param int         $tokenTimeoutVolatile
	 * @param string      $cookieName
	 */
	public function __construct(
		RequestAPI $requestApi,
		ResponseAPI $responseApi,
		$tokenTimeoutLongterm = self::TOKEN_TIMEOUT_LONGTERM,
		$tokenTimeoutVolatile = self::TOKEN_TIMEOUT_VOLATILE,
		$cookieName = self::COOKIE_NAME_AUTH_TOKEN
	) {
		$this->requestApi  = $requestApi;
		$this->responseApi = $responseApi;
		$this->tokenTimeoutLongterm = $tokenTimeoutLongterm;
		$this->tokenTimeoutVolatile = $tokenTimeoutVolatile;
		$this->cookieName = $cookieName;
	}

	/**
	 * Authenticate a user. If successful, the authentication token is set into a cookie and also returned.
	 *
	 * @param string $uniqueUserField
	 * @param string $password
	 * @param bool $volatile If true, use short token lifetime on server and create session cookie on client
	 *
	 * @return string
	 *
	 * @throws AuthenticationRateLimitedException
	 * @throws AuthenticationInvalidCredentialsException
	 * @throws AuthenticationException
	 */
	public function authenticate($uniqueUserField, $password, $volatile = false) {
		$apiClient = new CDEDisloClient();
		$authenticationResponse = $apiClient->userAuthenticate(
			$uniqueUserField,
			$password,
			$this->requestApi->getRemoteAddress()->__toString(),
			$volatile ? $this->tokenTimeoutVolatile : $this->tokenTimeoutLongterm,
			$volatile ? json_encode([self::KEY_VOLATILE => 1]) : '{}'
		);
		CDECookieCache::getInstance()->write(
			$this->cookieName,
			$authenticationResponse->getAuthToken(),
			$volatile ? 0 : $this->tokenTimeoutLongterm
		);
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
				CDECookieCache::getInstance()->delete($this->cookieName);
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
		// read from cookie
		if (!$authToken) {
			try {
				$authToken = CDECookieCache::getInstance()->read($this->cookieName);
				if (empty($authToken)) {
					CDECookieCache::getInstance()->delete($this->cookieName);
					throw new AuthenticationRequiredException();
				}
			} catch (CookieNotSetException $e) {
				throw new AuthenticationRequiredException();
			}
		}
		$apiClient = new CDEDisloClient();
		try {
			// verify and extend the token
			$extendResponse = $apiClient->userExtendToken($authToken, $this->requestApi->getRemoteAddress()->__toString());
			$authToken = $extendResponse->getAuthToken()->getToken();
			$metaInfo = json_decode($extendResponse->getAuthToken()->getMetaInfo(), true);

			// update auth cookie if not volatile
			if (!(isset($metaInfo[self::KEY_VOLATILE]) && $metaInfo[self::KEY_VOLATILE])) {
				// TODO: retrieve expiry date from token?
				CDECookieCache::getInstance()->write($this->cookieName, $authToken, $this->tokenTimeoutLongterm);
			}

			return $authToken;
		} catch (ObjectNotFoundException $e) {
			throw new AuthenticationRequiredException();
		} catch (InvalidTokenException $e) {
			throw new AuthenticationRequiredException();
		}
	}
}
