<?php

namespace Ixolit\Dislo\CDE\Exceptions;

class CookieSetFailedException extends \Exception implements CDEException {
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var int
	 */
	private $value;
	/**
	 * @var \Exception
	 */
	private $maxAge;

	public function __construct($name, $value, $maxAge) {
		parent::__construct('Failed setting cookie ' . $name . ' with maxAge ' . $maxAge . ' and value ' . $value);
		$this->name = $name;
		$this->value = $value;
		$this->maxAge = $maxAge;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return int
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @return \Exception
	 */
	public function getMaxAge() {
		return $this->maxAge;
	}



}