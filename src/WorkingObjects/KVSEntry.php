<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

class KVSEntry extends KVSKey {
	/**
	 * @var object
	 */
	private $value;

	/**
	 * @param string $key
	 * @param int    $version
	 * @param object $value
	 */
	public function __construct($key, $version, $value) {
		parent::__construct($key, $version);
		$this->value   = $value;
	}

	/**
	 * @return object
	 */
	public function getValue() {
		return $this->value;
	}
}