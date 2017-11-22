<?php

namespace Ixolit\Dislo\CDE\Context;

use Ixolit\CDE\Exceptions\GeoLookupFailedException;
use Ixolit\Dislo\Redirector\Base\Cookie;
use Ixolit\Dislo\Redirector\Base\Header;
use Ixolit\Dislo\Redirector\Base\RedirectorRequestInterface;
use Ixolit\Dislo\Redirector\Base\RequestParameter;

class PageRedirectorRequest implements RedirectorRequestInterface {

	/** @var Page */
	private $page;

	/**
	 * @param Page $page
	 */
	public function __construct(Page $page) {
		$this->page = $page;
	}

	/**
	 * @inheritDoc
	 */
	public function getScheme() {
		return $this->page->getScheme();
	}

	/**
	 * @inheritDoc
	 */
	public function getHost() {
		return $this->page->getVhost();
	}

	/**
	 * @inheritDoc
	 */
	public function getPath() {
		return $this->page->getFullPath();
	}

	/**
	 * @inheritDoc
	 */
	public function getRequestParameters() {
		$parameters = [];
		foreach ($this->page->getRequestAPI()->getRequestParameters() as $name => $value) {
			$parameters[] = (new RequestParameter())
				->setName($name)
				->setValue($value);
		}
		return $parameters;
	}

	/**
	 * @inheritDoc
	 */
	public function getMethod() {
		// TODO: implement in CDE
		return 'GET';
	}

	/**
	 * @inheritDoc
	 */
	public function getCookies() {
		$cookies = [];
		foreach ($this->page->getRequestAPI()->getCookies() as $cookie) {
			$cookies[] = (new Cookie())
				->setName($cookie->getName())
				->setValue($cookie->getValue());
		}
		return $cookies;
	}

	/**
	 * @inheritDoc
	 */
	public function getQuery() {
		return $this->page->getQueryString();
	}

	/**
	 * @inheritDoc
	 */
	public function getHeaders() {
		$headers = [];
		foreach ($this->page->getRequestAPI()->getHeaders() as $name => $value) {
			$headers[] = (new Header())
				->setName($name)
				->setValue($value);
		}
		return $headers;
	}

	/**
	 * @inheritDoc
	 */
	public function getHeader($key) {
		return $this->page->getRequestAPI()->getHeader($key);
	}

	/**
	 * @inheritDoc
	 */
	public function getIpBasedCountryCode() {
		try {
			$address = $this->page->getRequestAPI()->getRemoteAddress()->getAddress();
			$lookup = $this->page->getGeoLookupApi()->lookup($address);
			return $lookup->getCountry()->getIsoCode();
		}
		catch (GeoLookupFailedException $e) {
			return null;
		}
	}
}