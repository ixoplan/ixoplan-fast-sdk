<?php

namespace Ixolit\Dislo\CDE;

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
		$this->headers = $headers;
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
	public function withProtocolVersion($version) {
		$newObject = clone $this;
		$newObject->protocol = $version;
		return $newObject;
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
	public function withHeader($name, $value) {
		$newObject = clone $this;
		if (\is_array($value)) {
			$newObject->headers[$name] = $value;
		} else {
			$newObject->headers[$name] = [$value];
		}
		return $newObject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withAddedHeader($name, $value) {
		$newObject = clone $this;
		if (!isset($newObject->headers[$name])) {
			$newObject->headers[$name] = [];
		}
		if (\is_array($value)) {
			foreach ($value as $v) {
				$newObject->headers[$name][] = $v;
			}
		} else {
			$newObject->headers[$name][] = $value;
		}
		return $newObject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withoutHeader($name) {
		$newObject = clone $this;
		if (isset($newObject->headers[$name])) {
			unset($newObject->headers[$name]);
		}
		return $newObject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBody() {
		return $this->stream;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withBody(StreamInterface $body) {
		$newObject = clone $this;
		$newObject->stream = $body;
		return $newObject;
	}
}