<?php

namespace Ixolit\Dislo\CDE\Form;

use Ixolit\Dislo\CDE\Validator\MaximumLengthValidator;
use Ixolit\Dislo\CDE\Validator\SingleLineValidator;

/**
 * This class was ported from the Piccolo form library with permission.
 */
class TextField extends FormField {
	private $maxLength = null;
	private $maxLengthValidator = null;

	public function __construct($name) {
		parent::__construct($name);

		$this->addValidator(new SingleLineValidator());
	}

	public function setMaximumLength($maxLength) {
		$this->maxLength = $maxLength;
		if ($this->maxLengthValidator) {
			$this->removeValidator($this->maxLengthValidator);
		}
		if (!\is_null($maxLength)) {
			$this->maxLengthValidator = new MaximumLengthValidator((int)$maxLength);
			$this->addValidator($this->maxLengthValidator);
		} else {
			$this->maxLengthValidator = null;
		}
	}

	public function getType() {
		return 'text';
	}
}