<?php

namespace Ixolit\Dislo\CDE\Form;

use Ixolit\Dislo\CDE\Validator\SingleLineValidator;

/**
 * This class was ported from the Piccolo form library with permission.
 */
class EmailField extends TextField {
	public function __construct($name) {
		parent::__construct($name);

		$this->addValidator(new SingleLineValidator());
	}

	public function getType() {
		return 'email';
	}
}