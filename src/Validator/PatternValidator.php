<?php

namespace Ixolit\Dislo\CDE\Validator;

class PatternValidator implements FormValidator {
	const ERROR_PATTERN = 'pattern';

	/**
	 * @var string
	 */
	private $pattern;

	/**
	 * @param string $pattern
	 */
	public function __construct($pattern) {
		$this->pattern = $pattern;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getKey() {
		return self::ERROR_PATTERN;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isValid($value) {
		if (preg_match('/^' . $this->pattern . '$/D', $value)) {
			return true;
		}

		return false;
	}
}
