<?php

namespace Ixolit\Dislo\CDE\Auth;

use Ixolit\CDE\CDECookieCache;
use Ixolit\CDE\Interfaces\RequestAPI;
use Ixolit\CDE\Interfaces\ResponseAPI;
use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\Exceptions\AuthenticationException;
use Ixolit\Dislo\Exceptions\AuthenticationInvalidCredentialsException;
use Ixolit\Dislo\Exceptions\AuthenticationRateLimitedException;
use Ixolit\Dislo\Exceptions\InvalidTokenException;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;

/**
 * Helper for user authentication and verification. Stores the authentication token in a cookie.
 * The default mode uses the maximum token lifetime and renews the cookie frequently ("stay logged in").
 * The alternative volatile mode uses a short token lifetime and a session cookie.
 */
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
	 * @var string
	 */
	private $cookieDomain;

	/**
	 * @var int
	 */
	private $tokenTimeoutLongterm;

	/**
	 * @var int
	 */
	private $tokenTimeoutVolatile;

	/**
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
		$cookieName = self::COOKIE_NAME_AUTH_TOKEN,
		$cookieDomain = null
	) {
		$this->requestApi  = $requestApi;
		$this->responseApi = $responseApi;
		$this->tokenTimeoutLongterm = $tokenTimeoutLongterm;
		$this->tokenTimeoutVolatile = $tokenTimeoutVolatile;
		$this->cookieName = $cookieName;
		$this->cookieDomain = $cookieDomain;
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
			$volatile ? 0 : $this->tokenTimeoutLongterm,
			null,
			$this->cookieDomain
		);
		return $authenticationResponse->getAuthToken();
	}

	/**
	 * Invalidate an authentication token, either given or read from cookie, remove the cookie
	 *
	 * @param string|null $authToken
	 */
	public function deauthenticate($authToken = null) {

		// fallback to cookie
		if (!$authToken) {
			$authToken = CDECookieCache::getInstance()->read($this->cookieName);
		}

		// delete cookie anyway
		CDECookieCache::getInstance()->delete($this->cookieName);

		if ($authToken) {
			$apiClient = new CDEDisloClient();
			try {
				$apiClient->userDeauthenticate($authToken);
			} catch (ObjectNotFoundException $e) {
			}
		}
	}

	/**
	 * Check and extend an authentication token, either given or read from cookie, remove invalid cookie
	 *
	 * @param string|null $authToken
	 *
	 * @return string
	 *
	 * @throws AuthenticationRequiredException
	 */
	public function extendToken($authToken = null) {

		// fallback to cookie
		if (!$authToken) {
			$authToken = CDECookieCache::getInstance()->read($this->cookieName);
		}

		if ($authToken) {
			$apiClient = new CDEDisloClient();
			try {
				// verify and extend the token
				$extendResponse = $apiClient->userExtendToken($authToken, $this->requestApi->getRemoteAddress()->__toString());
				$authToken = $extendResponse->getAuthToken()->getToken();
				$metaInfo = json_decode($extendResponse->getAuthToken()->getMetaInfo(), true);

				// update auth cookie if not volatile
				if (!(isset($metaInfo[self::KEY_VOLATILE]) && $metaInfo[self::KEY_VOLATILE])) {
					// TODO: retrieve expiry date from token?
					CDECookieCache::getInstance()->write(
						$this->cookieName,
						$authToken,
						$this->tokenTimeoutLongterm,
						null,
						$this->cookieDomain
					);
				}

				return $authToken;

			} catch (ObjectNotFoundException $e) {
			} catch (InvalidTokenException $e) {
			}
		}

		// something is wrong with the token
		CDECookieCache::getInstance()->delete($this->cookieName);
		throw new AuthenticationRequiredException();
	}
}
