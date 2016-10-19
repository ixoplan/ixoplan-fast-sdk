<?php

namespace Ixolit\Dislo\CDE\PSR7;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * {@inheritdoc}
 */
class Response extends Message implements ResponseInterface {
	/**
	 * @var int
	 */
	private $status;

	public function __construct($status, array $headers, $body, $version) {
		parent::__construct($headers, $body, $version);
		$this->status = $status;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getStatusCode() {
		return $this->status;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withStatus($code, $reasonPhrase = '') {
		$newObject = clone $this;
		$newObject->status = $code;
		return $newObject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getReasonPhrase() {
		return '';
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
		return new Response(
			$this->getStatusCode(),
			$headers,
			$this->getBody(),
			$this->getProtocolVersion()
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

		return new Response(
			$this->getStatusCode(),
			$headers,
			$this->getBody(),
			$this->getProtocolVersion()
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

		return new Response(
			$this->getStatusCode(),
			$headers,
			$this->getBody(),
			$this->getProtocolVersion()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function withBody(StreamInterface $body) {
		return new Response(
			$this->getStatusCode(),
			$this->getHeaders(),
			$body,
			$this->getProtocolVersion()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function withProtocolVersion($version) {
		return new Response(
			$this->getStatusCode(),
			$this->getHeaders(),
			$this->getBody(),
			$version
		);
	}
}