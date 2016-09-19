<?php

namespace Ixolit\Dislo\CDE\Interfaces;

use Ixolit\Dislo\CDE\Exceptions\CookieNotSetException;
use Ixolit\Dislo\CDE\Exceptions\InformationNotAvailableInContextException;
use Ixolit\Dislo\CDE\WorkingObjects\Cookie;
use Ixolit\Dislo\CDE\WorkingObjects\Layout;

/**
 * The request API in the CDE gives access to the current HTTP request. Some functionality may not be available
 * depending on the current context.
 */
interface RequestAPI {
	/**
	 * Returns the resolved, normalized vhost name for the current request.
	 *
	 * @return string
	 *
	 * @throws InformationNotAvailableInContextException
	 */
	public function getVhost();

	/**
	 * Returns the fully qualified domain name for the current request, e.g. www.dislo.com
	 *
	 * @return string
	 *
	 * @throws InformationNotAvailableInContextException
	 */
	public function getFQDN();

	/**
	 * Returns the value of the cookie with the given name. If the request contains multiple cookies with the same
	 * name, the value of the first (request header order) cookie with the given name will be returned.
	 *
	 * @param string $name
	 *
	 * @return Cookie
	 *
	 * @throws CookieNotSetException
	 */
	public function getCookie($name);

	/**
	 * Returns all cookies contained in the current request.
	 *
	 * @return Cookie[]
	 */
	public function getCookies();

	/**
	 * Get language for the current request. Defaults to the default language on an error page.
	 *
	 * @return string
	 */
	public function getLanguage();

	/**
	 * Returns the current layout.
	 *
	 * @return Layout
	 *
	 * @throws InformationNotAvailableInContextException if no layout information is available in the current context.
	 */
	public function getLayout();

	/**
	 * Returns the link to the current page, optionally for a different language.
	 *
	 * @param string|null $lang
	 *
	 * @return string
	 *
	 * @throws InformationNotAvailableInContextException if the page link is not available in the current context.
	 */
	public function getPageLink($lang = null);

	/**
	 * Returns the path of the current page. E.g. this function would return "/resources" for the url
	 * "http://www.dislo.com/en/resources
	 *
	 * @return string
	 *
	 * @throws InformationNotAvailableInContextException if the page link is not available in the current context.
	 */
	public function getPagePath();
}
