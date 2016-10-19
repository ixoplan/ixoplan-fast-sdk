<?php

namespace Ixolit\Dislo\CDE\PSR7;

use Psr\Http\Message\StreamInterface;

abstract class Message {
	/**
	 * @var string[][]
	 */
	private $headers;

	/**
	 * @var string
	 */
	private $protocol;

	/**
	 * @var StreamInterface
	 */
	private $stream;

	public function __construct(
		array $headers = [],
		$body = null,
		$version = '1.1'
	) {
		$this->headers  = $headers;
		$this->protocol = $version;
		if ($body !== '' && $body !== null) {
			$this->stream = new StringStream($body);
		} else {
			$this->stream = new StringStream('');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getProtocolVersion() {
		return $this->protocol;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasHeader($name) {
		return (isset($this->headers[$name]));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHeader($name) {
		if ($this->hasHeader($name)) {
			return $this->headers[$name];
		} else {
			return [];
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHeaderLine($name) {
		if (!$this->hasHeader($name)) {
			return '';
		}

		return \implode(',', $this->headers[$name]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBody() {
		return $this->stream;
	}
}