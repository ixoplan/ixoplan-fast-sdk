<?php

namespace Ixolit\Dislo\CDE;

use Psr\Http\Message\UriInterface;

/**
 * {@inheritdoc}
 */
class Uri implements UriInterface {
	/**
	 * @var string
	 */
	private $userInfo;

	/**
	 * @var string
	 */
	private $scheme;

	/**
	 * @var string
	 */
	private $host;

	/**
	 * @var int
	 */
	private $port;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var string
	 */
	private $query;

	/**
	 * @var string
	 */
	private $fragment;

	public function __construct($scheme, $host, $port, $path, $query) {
		$this->scheme = $scheme;
		$this->host = $host;
		$this->port = $port;
		$this->path = $path;
		$this->query = $query;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getScheme() {
		return $this->scheme;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAuthority() {
		$authority = '';
		if ($this->getUserInfo()) {
			$authority .= $this->getUserInfo() . '@';
		}
		$authority .= $this->getHost();
		if (!($this->getScheme() == 'http' && $this->getPort() == 80) &&
			!($this->getScheme() == 'https' && $this->getPort() == 443)) {
			$authority .= ':' . $this->getPort();
		}
		return $authority;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUserInfo() {
		return $this->userInfo;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getQuery() {
		return $this->query;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFragment() {
		return $this->fragment;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withScheme($scheme) {
		$newObject = clone $this;
		$newObject->scheme = $scheme;
		return $newObject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withUserInfo($user, $password = null) {
		$newObject = clone $this;
		$newObject->userInfo = \urlencode($user) . ($password?':' . \urlencode($password):'');
		return $newObject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withHost($host) {
		$newObject = clone $this;
		$newObject->host = $host;
		return $newObject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withPort($port) {
		$newObject = clone $this;
		$newObject->port = (int)$port;
		return $newObject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withPath($path) {
		$newObject = clone $this;
		$newObject->path = $path;
		return $newObject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withQuery($query) {
		$newObject = clone $this;
		$newObject->query = $query;
		return $newObject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withFragment($fragment) {
		$newObject = clone $this;
		$newObject->fragment = $fragment;
		return $newObject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString() {
		return $this->getScheme() . '://' .
		$this->getAuthority() .
		$this->getPath() .
		($this->getQuery()?'?' . $this->getQuery():'') .
		($this->getFragment()?'#' . $this->getFragment():'');
	}
}