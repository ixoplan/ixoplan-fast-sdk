<?php

namespace Ixolit\Dislo\CDE\Validator;

class InArrayValidator implements FormValidator {

	private $values = [];

	/**
	 * @param array $values
	 */
	public function __construct(array $values) {
		$this->values = $values;
	}

	/**
	 * Return a unique key for the error type.
	 *
	 * @return string
	 */
	public function getKey() {
		return 'in-array';
	}

	/**
	 * Returns false if the validator failed to validate $value.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function isValid($value) {
		if (!$value) {
			return true;
		}
		return (\in_array($value, $this->values));
	}
}