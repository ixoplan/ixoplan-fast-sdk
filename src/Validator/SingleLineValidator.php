<?php

namespace Ixolit\Dislo\CDE\Validator;

/**
 * Validate that a value has only one line.
 */
class SingleLineValidator implements FormValidator {
	const ERROR_MULTILINE = 'multiline';

	/**
	 * {@inheritdoc}
	 */
	public function getKey() {
		return self::ERROR_MULTILINE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isValid($value) {
		if (\is_array($value)) {
			return false;
		}
		if (!$value) {
			return true;
		}

		return !\preg_match('/\n/', $value);
	}
}
