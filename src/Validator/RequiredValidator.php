<?php

namespace Ixolit\Dislo\CDE\Validator;

/**
 * Validates a form field not being empty.
 */
class RequiredValidator implements FormValidator {
	const ERROR_REQUIRED = 'required';

	/**
	 * {@inheritdoc}
	 */
	public function isValid($value) {
		return !empty($value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getKey() {
		return self::ERROR_REQUIRED;
	}
}