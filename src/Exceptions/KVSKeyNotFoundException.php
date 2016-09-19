<?php

namespace Ixolit\Dislo\CDE\Exceptions;

class KVSKeyNotFoundException extends \Exception implements CDEException {
	/**
	 * @var string
	 */
	private $key;

	/**
	 * @param string $key
	 */
	public function __construct($key) {
		parent::__construct('KVS key not found: ' . $key);
		$this->key = $key;
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}
}
