<?php

namespace Ixolit\Dislo\CDE\Context;

use Ixolit\Dislo\Redirector\Base\Cookie;
use Ixolit\Dislo\Redirector\Base\Header;
use Ixolit\Dislo\Redirector\Base\RedirectorResultInterface;
use Ixolit\Dislo\Redirector\Base\SessionVariable;

class PageRedirectorResult implements RedirectorResultInterface {

	/** @var Page */
	private $page;

	/**
	 * @param Page $page
	 */
	public function __construct(Page $page) {
		$this->page = $page;
	}

	/**
	 * @param int $statusCode
	 * @param string $location
	 * @return self
	 */
	public function sendRedirect($statusCode, $location) {
		$this->page->getResponseAPI()->setStatusCode($statusCode);
		$this->page->getResponseAPI()->setHeader('Location', $location);
		exit;
	}

	/**
	 * @param Cookie $cookie
	 * @return self
	 */
	public function setCookie($cookie) {
		$this->page->getResponseAPI()->setCookie(
			$cookie->getName(),
			$cookie->getValue(),
			$cookie->getExpirationDateTime()
				? $cookie->getExpirationDateTime()->getTimestamp() - time()
				: null,
			$cookie->getPath(),
			null,
			$cookie->isRequireSSL(),
			$cookie->isHttpOnly()
		);
		return $this;
	}

	/**
	 * @param Header $header
	 * @return self
	 */
	public function setHeader($header) {
		$this->page->getResponseAPI()->setHeader(
			$header->getName(),
			$header->getValue()
		);
		return $this;
	}

	/**
	 * @param SessionVariable $variable
	 * @return self
	 */
	public function setSessionVariable($variable) {
		$this->page->getTemporaryStorage()->setVariable(
			$variable->getName(),
			$variable->getValue()
		);
		return $this;
	}
}