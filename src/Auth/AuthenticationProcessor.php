<?php

namespace Ixolit\Dislo\CDE\Auth;

use Ixolit\CDE\CDECookieCache;
use Ixolit\CDE\Interfaces\RequestAPI;
use Ixolit\CDE\Interfaces\ResponseAPI;
use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\Client;
use Ixolit\Dislo\Exceptions\AuthenticationException;
use Ixolit\Dislo\Exceptions\AuthenticationInvalidCredentialsException;
use Ixolit\Dislo\Exceptions\AuthenticationRateLimitedException;
use Ixolit\Dislo\Exceptions\InvalidTokenException;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;
use Ixolit\Dislo\WorkingObjects\AuthToken;
use Ixolit\Dislo\WorkingObjects\User;

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
     * @var Client
     */
	private $client;

    /**
     * @param RequestAPI  $requestApi
     * @param ResponseAPI $responseApi
     * @param int         $tokenTimeoutLongterm
     * @param int         $tokenTimeoutVolatile
     * @param string      $cookieName
     * @param string|null $cookieDomain
     * @param Client|null $client
     */
	public function __construct(
		RequestAPI $requestApi,
		ResponseAPI $responseApi,
		$tokenTimeoutLongterm = self::TOKEN_TIMEOUT_LONGTERM,
		$tokenTimeoutVolatile = self::TOKEN_TIMEOUT_VOLATILE,
		$cookieName = self::COOKIE_NAME_AUTH_TOKEN,
		$cookieDomain = null,
        Client $client = null
	) {
		$this->requestApi  = $requestApi;
		$this->responseApi = $responseApi;
		$this->tokenTimeoutLongterm = $tokenTimeoutLongterm;
		$this->tokenTimeoutVolatile = $tokenTimeoutVolatile;
		$this->cookieName = $cookieName;
		$this->cookieDomain = $cookieDomain;
		$this->client = $client;
	}

    /**
     * @return Client
     */
	protected function getClient() {
	    if (!isset($this->client)) {
	        $this->client = new CDEDisloClient();
        }

        return $this->client;
    }

    /**
     * @return RequestAPI
     */
    protected function getRequestApi() {
	    return $this->requestApi;
    }

    /**
     * @return ResponseAPI
     */
    protected function getResponseApi() {
        return $this->responseApi;
    }

    /**
     * @return string
     */
    protected function getCookieName() {
        return $this->cookieName;
    }

    /**
     * @return null|string
     */
    protected function getCookieDomain() {
        return $this->cookieDomain;
    }

    /**
     * @return int
     */
    protected function getTokenTimeoutLongTerm() {
        return $this->tokenTimeoutLongterm;
    }

    /**
     * @return int
     */
    protected function getTokenTimeoutVolatile() {
        return $this->tokenTimeoutVolatile;
    }

	/**
	 * Authenticate a user. If successful, the authentication token is set into a cookie and also returned.
	 *
	 * @param string $uniqueUserField
	 * @param string $password
	 * @param bool   $volatile If true, use short token lifetime on server and create session cookie on client
	 * @param bool   $ignoreRateLimit
	 *
	 * @return string
	 * @throws AuthenticationException
	 * @throws AuthenticationInvalidCredentialsException
	 * @throws AuthenticationRateLimitedException
	 */
	public function authenticate($uniqueUserField, $password, $volatile = false, $ignoreRateLimit = false) {
		$authenticationResponse = $this->getClient()->userAuthenticate(
			$uniqueUserField,
			$password,
			$this->getRequestApi()->getRemoteAddress()->__toString(),
			$volatile ? $this->getTokenTimeoutVolatile() : $this->getTokenTimeoutLongTerm(),
			$volatile ? json_encode([self::KEY_VOLATILE => 1]) : '{}',
			$ignoreRateLimit
		);
		CDECookieCache::getInstance()->write(
			$this->getCookieName(),
			$authenticationResponse->getAuthToken(),
			$volatile ? 0 : $this->getTokenTimeoutLongTerm(),
			null,
			$this->getCookieDomain()
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
			$authToken = CDECookieCache::getInstance()->read($this->getCookieName());
		}

		// delete cookie anyway
		CDECookieCache::getInstance()->delete($this->getCookieName(), null, $this->getCookieDomain());

		if ($authToken) {
			try {
				$this->getClient()->userDeauthenticate($authToken);
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
			$authToken = CDECookieCache::getInstance()->read($this->getCookieName());
		}

		if ($authToken) {
			try {
				// verify and extend the token
				$extendResponse = $this->getClient()->userExtendToken(
				    $authToken,
                    $this->getRequestApi()->getRemoteAddress()->__toString()
                );
				$authToken = $extendResponse->getAuthToken()->getToken();

				$this->updateAuthTokenCookie($extendResponse->getAuthToken());

				return $authToken;

			} catch (ObjectNotFoundException $e) {
			} catch (InvalidTokenException $e) {
			}
		}

		// something is wrong with the token
		CDECookieCache::getInstance()->delete($this->getCookieName(), null, $this->getCookieDomain());
		throw new AuthenticationRequiredException();
	}

    /**
     * @param string|null $authToken
     *
     * @return User
     *
     * @throws AuthenticationRequiredException
     */
	public function getAuthenticatedUser($authToken = null) {
	    if (!$authToken) {
	        $authToken = CDECookieCache::getInstance()->read($this->getCookieName());
        }

        if ($authToken) {
	        try {
                $user = $this->getClient()->userGetAuthenticated(
                    $authToken,
                    $this->getRequestApi()->getRemoteAddress()->__toString()
                )->getUser();

                $this->updateAuthTokenCookie($user->getAuthToken());

                return $user;
            } catch (ObjectNotFoundException $e) {
            } catch (InvalidTokenException $e) {
            }
        }

        CDECookieCache::getInstance()->delete($this->getCookieName(), null, $this->getCookieDomain());
	    throw new AuthenticationRequiredException();
    }

    /**
     * @param AuthToken $authToken
     *
     * @return $this
     */
    protected function updateAuthTokenCookie(AuthToken $authToken) {
        $authTokenMetaInfo = \json_decode($authToken->getMetaInfo(), true);

        // update auth cookie if not volatile
        if (!(isset($authTokenMetaInfo[self::KEY_VOLATILE]) && $authTokenMetaInfo[self::KEY_VOLATILE])) {
            // TODO: retrieve expiry date from token?
            CDECookieCache::getInstance()->write(
                $this->getCookieName(),
                $authToken->getToken(),
                $this->getTokenTimeoutLongTerm(),
                null,
                $this->getCookieDomain()
            );
        }

        return $this;
    }
}
