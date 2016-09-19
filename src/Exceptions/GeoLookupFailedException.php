<?php

namespace Ixolit\Dislo\CDE\Exceptions;

class GeoLookupFailedException extends \Exception implements GeoException {
	/**
	 * @var string
	 */
	private $ip;

	/**
	 * @param string $ip
	 */
	public function __construct($ip) {
		parent::__construct('GeoIP lookup failed for: ' . $ip);
		$this->ip = $ip;
	}

	/**
	 * @return string
	 */
	public function getIp() {
		return $this->ip;
	}
}