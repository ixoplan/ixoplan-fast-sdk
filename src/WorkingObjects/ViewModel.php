<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

class ViewModel {
	/**
	 * @var array
	 */
	private $data = [];

	/**
	 * @param array $data
	 */
	public function __construct($data) {
		$this->data = $data;
	}

	/**
	 * Retrieve a variable, and encode it for embedding into an URL.
	 *
	 * @param string $variable
	 *
	 * @return string
	 */
	public function url($variable) {
		return \urlencode($this->raw($variable));
	}

	/**
	 * Retrieve a variable, and encode it for embedding into an URL, then encode it for HTML.
	 *
	 * @param string $variable
	 *
	 * @return string
	 */
	public function htmlUrl($variable) {
		return \html(\urlencode($this->raw($variable)));
	}

	/**
	 * Retrieve a variable, and encode it for embedding into HTML.
	 *
	 * @param string $variable
	 *
	 * @return string
	 */
	public function html($variable) {
		return \html($this->raw($variable));
	}

	/**
	 * Retrieve a variable, and encode it for XML.
	 *
	 * @param string $variable
	 *
	 * @return string
	 */
	public function xml($variable) {
		return \xml($this->raw($variable));
	}

	/**
	 * Retrieve a variable, encoded for embedding into JavaScript code.
	 *
	 * @param string $variable
	 *
	 * @return string
	 */
	public function js($variable) {
		return \js($this->raw($variable));
	}

	/**
	 * Retrieve an unencoded variable. Use with care.
	 *
	 * @param string $variable
	 *
	 * @return string
	 */
	public function raw($variable) {
		return $this->data[$variable];
	}
}
