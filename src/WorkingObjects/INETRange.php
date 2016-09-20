<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

use Ixolit\Dislo\CDE\Exceptions\INETAddressTypeException;
use Ixolit\Dislo\CDE\Exceptions\INETInvalidMaskException;

/**
 * A network range representation for both IPv4 and IPv6 ranges. This class was ported from the Opsbears Foundation
 * library with authorization.
 */
class INETRange {
	/**
	 * The INETAddress, that is used as a base for the range.
	 * @var INETAddress
	 */
	protected $address;

	/**
	 * The mask that is used to get the binary form
	 * @var int
	 */
	protected $mask;

	/**
	 * The binary representation for the network base address
	 * @var string
	 */
	protected $binary;

	/**
	 * Creates a new network range representation.
	 *
	 * @param INETAddress $address
	 * @param int         $mask
	 *
	 * @throws INETInvalidMaskException if the mask is invalid for the base address
	 */
	public function __construct(INETAddress $address, $mask) {
		$this->address = $address;
		$this->mask    = (int)$mask;
		$this->binary  = $this->address->getAsBinary($mask);
	}

	/**
	 * Returns, if the INETAddress provided is within the network range.
	 *
	 * @param INETAddress $address
	 *
	 * @throws INETAddressTypeException if the range and the address is not compatible.
	 * @return bool
	 */
	public function containsAddress(INETAddress $address) {
		if (\get_class($address) != get_class($this->address)) {
			throw new INETAddressTypeException($address, get_class($this->address));
		}
		if (\strcmp($address->getAsBinary($this->mask), $this->binary) == 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->address->__toString() . '/' . $this->mask;
	}
}
