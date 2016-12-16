<?php

namespace Ixolit\Dislo\CDE\Validator;

use Ixolit\Dislo\CDE\Form\FormField;
use Ixolit\Dislo\CDE\Validator\RequiredValidator;

/**
 * Set a field to quired if another field has a certain value.
 */
class RequiredIfOtherValidator extends RequiredValidator {
	private $otherField;
	private $otherValue;

	/**
	 * @param FormField $otherField
	 * @param string $otherValue
	 */
	public function __construct(FormField $otherField, $otherValue) {
		$this->otherField = $otherField;
		$this->otherValue = $otherValue;
	}

	/**
	 * Returns false if the validator failed to validate $value.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function isValid($value) {
		if ($this->otherField->getValue() != $value) {
			return true;
		}
		return parent::isValid($value);
	}
}