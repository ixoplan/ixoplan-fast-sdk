<?php

namespace Ixolit\Dislo\CDE;

use Psr\Http\Message\ResponseInterface;

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
}