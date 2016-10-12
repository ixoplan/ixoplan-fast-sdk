<?php

namespace Ixolit\Dislo\CDE\Validator;

/**
 * This class was ported from the Piccolo form library with permission.
 */
interface FormValidator {
	/**
	 * Return a unique key for the error type.
	 *
	 * @return string
	 */
	public function getKey();

	/**
	 * Returns false if the validator failed to validate $value.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function isValid($value);
}
