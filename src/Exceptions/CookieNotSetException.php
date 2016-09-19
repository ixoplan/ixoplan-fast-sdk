<?php

namespace Ixolit\Dislo\CDE\Exceptions;

class CookieNotSetException extends \Exception implements CDEException {
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param string $name
	 */
	public function __construct($name) {
		parent::__construct('Cookie not set: ' . $name);
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
}