<?php

namespace Ixolit\Dislo\CDE\PSR7;

use Ixolit\Dislo\CDE\Exceptions\CDEFeatureNotSupportedException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * {@inheritdoc}
 */
class ServerRequest extends Message implements ServerRequestInterface {
	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var Uri
	 */
	private $uri;

	/**
	 * @var array
	 */
	private $serverParams;

	/**
	 * @var array
	 */
	private $cookies;

	public function __construct(
		$method,
		UriInterface $uri,
		array $headers = [],
		$body = null,
		$version = '1.1',
		$cookies = [],
		array $serverParams = []
	) {
		parent::__construct(
			$headers,
			$body,
			$version);
		$this->method = strtoupper($method);
		$this->uri = $uri;
		$this->cookies = $cookies;
		$this->serverParams = $serverParams;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRequestTarget() {
		return $this->uri->getQuery() .
			($this->uri->getQuery()?'?' . $this->uri->getQuery():'');
	}

	/**
	 * {@inheritdoc}
	 */
	public function withRequestTarget($requestTarget) {
		$target = \explode('?', $requestTarget);
		$uri = $this->uri->withPath($target[0]);
		if (isset($target[1])) {
			$uri = $uri->withQuery($target[1]);
		}
		return $this->withUri($uri);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withMethod($method) {
		return new ServerRequest(
			$method,
			$this->getUri(),
			$this->getHeaders(),
			$this->getBody(),
			$this->getProtocolVersion(),
			$this->getCookieParams(),
			$this->getServerParams()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUri() {
		return $this->uri;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withUri(UriInterface $uri, $preserveHost = false) {
		$newObject = new ServerRequest(
			$this->getMethod(),
			$uri,
			$this->getHeaders(),
			$this->getBody(),
			$this->getProtocolVersion(),
			$this->getCookieParams(),
			$this->getServerParams()
		);
		if (!$preserveHost) {
			$newObject = $newObject->withHeader('Host', $uri->getHost());
		}
		return $newObject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getServerParams() {
		return $this->serverParams;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCookieParams() {
		return $this->cookies;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withCookieParams(array $cookies) {
		return new ServerRequest(
			$this->getMethod(),
			$this->getUri(),
			$this->getHeaders(),
			$this->getBody(),
			$this->getProtocolVersion(),
			$cookies,
			$this->getServerParams()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getQueryParams() {
		\parse_str($this->getUri()->getQuery(), $data);
		return $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withQueryParams(array $query) {
		$uri = $this->uri->withQuery(\http_build_query($query));
		return $this->withUri($uri);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUploadedFiles() {
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function withUploadedFiles(array $uploadedFiles) {
		throw new CDEFeatureNotSupportedException('File uploads are not supported in the CDE.');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParsedBody() {
		if ($this->hasHeader('Content-Type') == 'application/x-www-form-urlencoded') {
			\parse_str($this->getBody()->getContents(), $data);
			return $data;
		} else {
			parse_str($this->uri->getQuery(), $data);
			return $data;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function withParsedBody($data) {
		return $this->withBody(new StringStream(\http_build_query($data)));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAttributes() {
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAttribute($name, $default = null) {
		throw new CDEFeatureNotSupportedException('ServerRequest attributes');
	}

	/**
	 * {@inheritdoc}
	 */
	public function withAttribute($name, $value) {
		throw new CDEFeatureNotSupportedException('ServerRequest attributes');
	}

	/**
	 * {@inheritdoc}
	 */
	public function withoutAttribute($name) {
		throw new CDEFeatureNotSupportedException('ServerRequest attributes');
	}


	/**
	 * {@inheritdoc}
	 */
	public function withHeader($name, $value) {
		$headers = $this->getHeaders();
		if (\is_array($value)) {
			$headers[$name] = $value;
		} else {
			$headers[$name] = [$value];
		}
		return new ServerRequest(
			$this->getMethod(),
			$this->getUri(),
			$headers,
			$this->getBody(),
			$this->getProtocolVersion(),
			$this->getCookieParams(),
			$this->getServerParams()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function withAddedHeader($name, $value) {
		$headers = $this->getHeaders();
		if (!isset($headers[$name])) {
			$headers[$name] = [];
		}
		if (\is_array($value)) {
			foreach ($value as $v) {
				$headers[$name][] = $v;
			}
		} else {
			$headers[$name][] = $value;
		}

		return new ServerRequest(
			$this->getMethod(),
			$this->getUri(),
			$headers,
			$this->getBody(),
			$this->getProtocolVersion(),
			$this->getCookieParams(),
			$this->getServerParams()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function withoutHeader($name) {
		$headers = $this->getHeaders();
		if (isset($headers[$name])) {
			unset($headers[$name]);
		}

		return new ServerRequest(
			$this->getMethod(),
			$this->getUri(),
			$headers,
			$this->getBody(),
			$this->getProtocolVersion(),
			$this->getCookieParams(),
			$this->getServerParams()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function withBody(StreamInterface $body) {
		return new ServerRequest(
			$this->getMethod(),
			$this->getUri(),
			$this->getHeaders(),
			$body,
			$this->getProtocolVersion(),
			$this->getCookieParams(),
			$this->getServerParams()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function withProtocolVersion($version) {
		return new ServerRequest(
			$this->getMethod(),
			$this->getUri(),
			$this->getHeaders(),
			$this->getBody(),
			$version,
			$this->getCookieParams(),
			$this->getServerParams()
		);
	}
}