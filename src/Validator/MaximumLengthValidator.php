<?php

namespace Ixolit\Dislo\CDE\Validator;

class MaximumLengthValidator implements FormValidator {
	const ERROR_MAXIMUM_LENGTH = 'maximum-length';

	/**
	 * @var int
	 */
	private $maxLength;

	public function __construct($maxLength) {
		$this->maxLength = (int)$maxLength;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isValid($value) {
		return (strlen($value)<=$this->maxLength);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getKey() {
		return self::ERROR_MAXIMUM_LENGTH;
	}
}
