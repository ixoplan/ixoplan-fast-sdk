<?php

namespace Ixolit\Dislo\CDE\Exceptions;

/**
 * This exception indicates, that an invalid INET mask was provided. This class was ported from the Opsbears Foundation
 * library with authorization.
 */
class INETInvalidMaskException extends InvalidValueException implements INETException {
	/**
	 * The invalid mask
	 * @var mixed
	 */
	protected $mask;

	/**
	 * Sets the mask, that has caused the error
	 * @param mixed $mask
	 */
	public function __construct($mask) {
		parent::__construct($mask, 'valid network mask');
		$this->mask = $mask;
	}

	/**
	 * Get the mask, that has caused the problem.
	 * @return mixed
	 */
	public function getMask() {
		return $this->mask;
	}
}
