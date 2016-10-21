<?php

namespace Ixolit\Dislo\CDE\Validator;

class ExactValueValidator implements FormValidator {
	/**
	 * @var string
	 */
	private $value;

	/**
	 * @param string $value
	 */
	public function __construct($value) {
		$this->value = $value;
	}

	/**
	 * Return a unique key for the error type.
	 *
	 * @return string
	 */
	public function getKey() {
		return 'exact-value';
	}

	/**
	 * Returns false if the validator failed to validate $value.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function isValid($value) {
		return ($value == $this->value);
	}
}
