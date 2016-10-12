<?php

namespace Ixolit\Dislo\CDE\Validator;

class MinimumLengthValidator implements FormValidator {
	const ERROR_MINIMUM_LENGTH = 'minimum-length';

	/**
	 * @var int
	 */
	private $minLength;

	public function __construct($minLength) {
		$this->minLength = (int)$minLength;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isValid($value) {
		return (strlen($value)>=$this->minLength);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getKey() {
		return self::ERROR_MINIMUM_LENGTH;
	}
}
