<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\CDEFeatureNotSupportedException;
use Ixolit\Dislo\CDE\Exceptions\CookieNotSetException;
use Ixolit\Dislo\CDE\Exceptions\InformationNotAvailableInContextException;
use Ixolit\Dislo\CDE\Interfaces\RequestAPI;
use Ixolit\Dislo\CDE\WorkingObjects\Cookie;
use Ixolit\Dislo\CDE\WorkingObjects\INETAddress;
use Ixolit\Dislo\CDE\WorkingObjects\Layout;
use Ixolit\Dislo\CDE\WorkingObjects\Map;

/**
 * This API implements the request API using the CDE API calls.
 */
class CDERequestAPI implements RequestAPI  {
	/**
	 * {@inheritdoc}
	 */
	public function getVhost() {
		if (!\function_exists('getVhost')) {
			throw new CDEFeatureNotSupportedException('getVhost');
		}
		$vhost = getVhost();
		if ($vhost === null) {
			throw new InformationNotAvailableInContextException('vhost');
		}
		return $vhost;
	}

	public function getFQDN() {
		if (!\function_exists('getFQDN')) {
			throw new CDEFeatureNotSupportedException('getFQDN');
		}
		$fqdn = getFQDN();
		if ($fqdn === null) {
			throw new InformationNotAvailableInContextException('FQDN');
		}
		return $fqdn;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCookie($name) {
		if (!\function_exists('getCookie')) {
			throw new CDEFeatureNotSupportedException('getCookie');
		}
		$value = $this->getCookie($name);
		if ($value === null) {
			throw new CookieNotSetException($name);
		}

		return new Cookie(
			$name,
			$value
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCookies() {
		if (!\function_exists('getCookies')) {
			throw new CDEFeatureNotSupportedException('getCookies');
		}
		$cookies = getCookies();
		$result = [];
		foreach ($cookies as $name => $values) {
			foreach ($values as $value) {
				$result[] = new Cookie($name, $value);
			}
		}
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLanguage() {
		if (!\function_exists('getCurrentLanguage')) {
			throw new CDEFeatureNotSupportedException('getCurrentLanguage');
		}
		return \getCurrentLanguage();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLayout() {
		if (!\function_exists('getCurrentLayout')) {
			throw new CDEFeatureNotSupportedException('getCurrentLayout');
		}
		$layoutName = \getCurrentLayout();
		if ($layoutName === null) {
			throw new InformationNotAvailableInContextException('layout');
		}

		return new Layout(
			$this->getVhost(),
			$layoutName
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPageLink($lang = null) {
		if (!\function_exists('getCurrentPageLink')) {
			throw new CDEFeatureNotSupportedException('getCurrentPageLink');
		}

		$link = getCurrentPageLink($lang);

		if ($link === null) {
			throw new InformationNotAvailableInContextException('current page link');
		}

		return $link;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPagePath() {
		if (!\function_exists('getCurrentPagePath')) {
			throw new CDEFeatureNotSupportedException('getCurrentPagePath');
		}

		$path = getCurrentPagePath();

		if ($path === null) {
			throw new InformationNotAvailableInContextException('current page path');
		}

		return $path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRemoteAddress() {
		if (!\function_exists('getRemoteAddress')) {
			throw new CDEFeatureNotSupportedException('getRemoteAddress');
		}

		$address = getRemoteAddress();
		if ($address === null) {
			throw new InformationNotAvailableInContextException('remote address');
		}
		return INETAddress::getFromString($address);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRequestParameters() {
		if (!\function_exists('getRequestParameterList')) {
			throw new CDEFeatureNotSupportedException('getRequestParameterList');
		}

		$data = getRequestParameterList(null);
		if ($data === null) {
			throw new InformationNotAvailableInContextException('request parameter');
		}
		return $data;
	}
}
