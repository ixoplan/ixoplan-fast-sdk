<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

class KVSKey {
	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var int
	 */
	private $version;

	/**
	 * @param string $key
	 * @param int    $version
	 */
	public function __construct($key, $version) {
		$this->key     = $key;
		$this->version = $version;
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return int
	 */
	public function getVersion() {
		return $this->version;
	}
}
