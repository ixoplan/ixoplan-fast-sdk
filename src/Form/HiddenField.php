<?php

namespace Ixolit\Dislo\CDE\Form;

use Ixolit\Dislo\CDE\Validator\MaximumLengthValidator;
use Ixolit\Dislo\CDE\Validator\PatternValidator;
use Ixolit\Dislo\CDE\Validator\SingleLineValidator;

/**
 * This class was ported from the Piccolo form library with permission.
 */
class HiddenField extends FormField {
	public function getType() {
		return 'hidden';
	}
}