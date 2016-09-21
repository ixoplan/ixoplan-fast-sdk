<?php

namespace Ixolit\Dislo\CDE\Exceptions;

class InvalidStatusCodeException extends \Exception implements CDEException {
	/**
	 * @var int
	 */
	private $statusCode;

	/**
	 * @param int $statusCode
	 */
	public function __construct($statusCode) {
		parent::__construct('Invalid HTTP status code: ' . $statusCode);

		$this->statusCode = $statusCode;
	}

	/**
	 * @return int
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}
}