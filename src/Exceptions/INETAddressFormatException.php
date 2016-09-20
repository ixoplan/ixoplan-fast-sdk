<?php

namespace Ixolit\Dislo\CDE\Exceptions;

/**
 * This exception indicates, that an address is of invalid format. This class was ported from the Opsbears Foundation
 * library with authorization.
 */
class INETAddressFormatException extends InvalidValueException implements INETException {
	/**
	 * The address in question.
	 * @var string
	 */
	protected $address;

	/**
	 * Sets the address that has caused this error.
	 * @param string $address
	 */
	public function __construct($address) {
		parent::__construct($address);
		$this->address = $address;
	}

	/**
	 * Get the address, that has caused this exception.
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}
}
