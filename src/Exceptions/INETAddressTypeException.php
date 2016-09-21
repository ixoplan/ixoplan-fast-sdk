<?php

namespace Ixolit\Dislo\CDE\Exceptions;

use Ixolit\Dislo\CDE\WorkingObjects\INETAddress;

/**
 * This exception indicates, that an INETAddress is not of the required type. This class was ported from the Opsbears
 * Foundation library with authorization.
 */
class INETAddressTypeException extends InvalidTypeException implements INETException {
	/**
	 * Sets the address that has caused this error.
	 *
	 * @param INETAddress $address
	 * @param string      $required
	 */
	public function __construct(INETAddress $address, $required) {
		parent::__construct('Address ' . \get_class($address) . ' is not of required type: ' . $required);
	}
}
