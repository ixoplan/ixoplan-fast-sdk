<?php

namespace Ixolit\Dislo\CDE\Form;

use Psr\Http\Message\ServerRequestInterface;

/**
 * This class was ported from the Piccolo form library with permission.
 */
abstract class Form {
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
			$fieldErrors = $field->setFromRequest($request);
			if ($fieldErrors) {
				$errors[$field->getName()] = $fieldErrors;
			}
		}
		return $errors;
	}
}
