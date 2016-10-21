<?php

namespace Ixolit\Dislo\CDE\Form;

use Ixolit\Dislo\CDE\Validator\MaximumLengthValidator;
use Ixolit\Dislo\CDE\Validator\PatternValidator;
use Ixolit\Dislo\CDE\Validator\SingleLineValidator;

class CheckboxField extends FormField {
	public function __construct($name) {
		parent::__construct($name);
	}

	public function getType() {
		return 'checkbox';
	}
}