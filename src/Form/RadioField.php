<?php

namespace Ixolit\Dislo\CDE\Form;

use Ixolit\Dislo\CDE\Validator\InArrayValidator;

class RadioField extends ChoiceField  {
	/**
	 * {@inheritdoc}
	 */
	public function getType() {
		return 'radio';
	}
}