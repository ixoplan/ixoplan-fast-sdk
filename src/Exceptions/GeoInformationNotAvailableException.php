<?php

namespace Ixolit\Dislo\CDE\Exceptions;

/**
 * Indicates that a specific aspect of geo information (e.g. city) is not available. This is different from
 * GeoLookupFailedException, which indicates that no geo information is available at all.
 */
class GeoInformationNotAvailableException extends \Exception implements GeoException {
	/**
	 * @var string
	 */
	private $aspect;
	/**
	 * @var int
	 */
	private $ip;

	public function __construct($aspect, $ip) {
		parent::__construct(\ucfirst($aspect) . ' information is not available for ' . $ip);
		$this->aspect = $aspect;
		$this->ip = $ip;
	}

	/**
	 * @return string
	 */
	public function getAspect() {
		return $this->aspect;
	}

	/**
	 * @return int
	 */
	public function getIp() {
		return $this->ip;
	}
}