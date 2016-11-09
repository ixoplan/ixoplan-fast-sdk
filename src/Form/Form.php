<?php

namespace Ixolit\Dislo\CDE\Form;

use Ixolit\Dislo\CDE\Validator\CSRFTokenValidator;
use Ixolit\Dislo\CDE\Validator\ExactValueValidator;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This class was ported from the Piccolo form library with permission.
 */
abstract class Form {
	/**
	 * @var CSRFTokenProvider
	 */
	private $csrfTokenProvider;
	/**
	 * @var string
	 */
	private $action;
	/**
	 * @var string
	 */
	private $method;

	/**
	 * Generic, form errors.
	 *
	 * @var string[]
	 */
	private $errors = [];

	/**
	 * @param string $action
	 * @param string $method
	 * @param CSRFTokenProvider $csrfTokenProvider
	 */
	public function __construct($action = '', $method = 'POST', CSRFTokenProvider $csrfTokenProvider) {
		$this->csrfTokenProvider = $csrfTokenProvider;
		$this->action = $action;
		$this->method = $method;

		if ($this->method == 'POST') {
			$csrfField = new HiddenField('csrf-token');
			$csrfField->addValidator(new CSRFTokenValidator($csrfTokenProvider->getCSRFToken()));
			$csrfField->setValue($csrfTokenProvider->getCSRFToken());
			//Don't transfer the value back to the form.
			$csrfField->setMasked(true);
			$this->addField($csrfField);
		}
		$formField = new HiddenField('_form');
		$formField->setValue($this->getKey());
		$formField->setMasked(true);
		$this->addField($formField);
	}

	/**
	 * @var FormField[]
	 */
	private $fields = [];

	/**
	 * Return a unique key for this form.
	 *
	 * @return string
	 */
	abstract public function getKey();

	/**
	 * Validate the form and return a list of error codes.
	 *
	 * @return array
	 */
	public function validate() {
		$errors = [];
		foreach ($this->fields as $field) {
			$errors[$field->getName()] = $field->validate();
		}
		return $errors;
	}

	protected function addField(FormField $field) {
		$this->fields[$field->getName()] = $field;
	}

	/**
	 * @return FormField[]
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * @param string $name
	 *
	 * @return FormField[]
	 */
	public function getFieldsByName($name) {
		$result = [];
		foreach ($this->fields as $field) {
			if ($field->getName() == $name) {
				$result[] = $field;
			}
		}
		return $result;
	}

	/**
	 * @param string $name
	 *
	 * @return FormField
	 * @throws \Exception
	 */
	public function getFirstFieldByName($name) {
		foreach ($this->fields as $field) {
			if ($field->getName() == $name) {
				return $field;
			}
		}
		throw new \Exception('Form field not found: ' . $name);
	}

	public function getValueByName($name) {
		$fields = $this->getFieldsByName($name);
		$value = null;
		foreach ($fields as $field) {
			if (!\is_null($value = $field->getValue())) {
				break;
			}
		}
		return $value;
	}

	public function setFromRequest(ServerRequestInterface $request) {
		$errors = [];
		foreach ($this->fields as $field) {
			$field->setErrors([]);
			$fieldErrors = $field->setFromRequest($request);
			if ($fieldErrors) {
				$errors[$field->getName()] = $fieldErrors;
			}
		}
		return $errors;
	}

	/**
	 * Return the form-specific errors. Does not return the field errors.
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * @param string[] $errors
	 */
	public function setErrors($errors) {
		$this->errors = $errors;
	}

	/**
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}
}
