<?php

namespace Ixolit\Dislo\CDE\Form;

use Ixolit\Dislo\CDE\Validator\InArrayValidator;

class DropDownField extends FormField {
	private $values = [];
	private $valuesValidator = null;

	/**
	 * @param array $values
	 */
	public function setValues($values) {
		$this->values = $values;
		if ($this->valuesValidator) {
			$this->removeValidator($this->valuesValidator);
		}
		$this->valuesValidator = new InArrayValidator(\array_keys($values));
		$this->addValidator($this->valuesValidator);
	}

	/**
	 * @return array
	 */
	public function getValues() {
		return $this->values;
	}

	/**
	 * Return the HTML form element type.
	 *
	 * @return string
	 */
	public function getType() {
		return 'dropdown';
	}
}