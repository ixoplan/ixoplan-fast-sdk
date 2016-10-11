<?php

namespace Ixolit\Dislo\CDE\Form;

use Ixolit\Dislo\CDE\Validator\SingleLineValidator;

class TextField extends FormField {
	public function __construct($name) {
		parent::__construct($name);

		$this->addValidator(new SingleLineValidator());
	}

	public function getType() {
		return 'text';
	}
}