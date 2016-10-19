<?php

namespace Ixolit\Dislo\CDE\PSR7;

use Ixolit\Dislo\CDE\Exceptions\CDEFeatureNotSupportedException;
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
		return new Uri(
			$scheme,
			$this->getHost(),
			$this->getPort(),
			$this->getPath(),
			$this->getQuery()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function withUserInfo($user, $password = null) {
		throw new CDEFeatureNotSupportedException('user info in PSR-7 objects');
	}

	/**
	 * {@inheritdoc}
	 */
	public function withHost($host) {
		return new Uri(
			$this->getScheme(),
			$host,
			$this->getPort(),
			$this->getPath(),
			$this->getQuery()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function withPort($port) {
		return new Uri(
			$this->getScheme(),
			$this->getHost(),
			$port,
			$this->getPath(),
			$this->getQuery()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function withPath($path) {
		return new Uri(
			$this->getScheme(),
			$this->getHost(),
			$this->getPort(),
			$path,
			$this->getQuery()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function withQuery($query) {
		return new Uri(
			$this->getScheme(),
			$this->getHost(),
			$this->getPort(),
			$this->getPath(),
			$query
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function withFragment($fragment) {
		throw new CDEFeatureNotSupportedException('URI fragment');
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