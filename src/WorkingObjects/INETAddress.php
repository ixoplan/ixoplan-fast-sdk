<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

use Ixolit\Dislo\CDE\Exceptions\INETAddressFormatException;
use Ixolit\Dislo\CDE\Exceptions\INETAddressTypeException;
use Ixolit\Dislo\CDE\Exceptions\InvalidTypeException;

/**
 * This class is an abstract for the IPv4 and IPv6 address classes created as INET4Address and INET6Address. For more
 * information about IP addresses see http://en.wikipedia.org/wiki/IP_address . This class was ported from the
 * Opsbears Foundation library with authorization.
 */
abstract class INETAddress {
	/**
	 * Initialize the INETAddress instance with an address from a string.
	 *
	 * @param string $address
	 */
	public function __construct($address = '') {
		$this->setAddress($address);
	}

	/**
	 * Set the raw address.
	 *
	 * @param string $address
	 *
	 * @throws INETAddressFormatException
	 *
	 * @return INETAddress
	 */
	abstract public function setAddress($address);

	/**
	 * Returns a typed IP address from a string
	 *
	 * @param string $ipAddress
	 *
	 * @return INETAddress
	 *
	 * @throws INETAddressFormatException
	 */
	public static function getFromString($ipAddress) {
		try {
			return new INET4Address($ipAddress);
		} catch (INETAddressFormatException $e) {
			return new INET6Address($ipAddress);
		}
	}

	/**
	 * Checks, if the address is a unicast or a broadcast address. (Latter is only valid for IPv4, IPv6 doesn't know
	 * broadcast, only multicast.)
	 *
	 * @return bool
	 */
	abstract function isUnicast();

	/**
	 * Checks, if the address is a multicast address.
	 * @return bool
	 */
	abstract public function isMulticast();

	/**
	 * Checks, if the address is a loopback address
	 * @return bool
	 */
	abstract public function isLoopback();

	/**
	 * Checks, if the address is globally routable.
	 * @return bool
	 */
	abstract public function isGloballyRoutable();

	/**
	 * Check, if the address is contained in any of the ranges.
	 * If an array member of $ranges is not an instance of INETRange, it is silently discarded.
	 *
	 * @param INETRange[] $ranges if INETRange $ranges
	 *
	 * @return boolean
	 */
	public function isInRanges($ranges) {
		$contained = false;
		foreach ($ranges as &$range) {
			if ($range instanceof INETRange) {
				if ($this->isInRange($range)) {
					$contained = true;
					break;
				}
			} else {
				throw new InvalidTypeException($range, 'INETRange');
			}
		}
		return $contained;
	}

	/**
	 * Checks, if this address is within the given INETRange
	 *
	 * @param INETRange $range
	 *
	 * @throws INETAddressTypeException if the supplied range is not of the same type.
	 * @return bool
	 */
	public function isInRange(INETRange $range) {
		return $range->containsAddress($this);
	}

	/**
	 * Get address as string
	 * @return string
	 */
	public function __toString() {
		return $this->getAddress();
	}

	/**
	 * Get normalized string representation of address.
	 * @return string
	 */
	abstract public function getAddress();

	/**
	 * Checks, if two INETAddress instances contain the same IP address.
	 *
	 * @param INETAddress $address
	 *
	 * @return bool
	 */
	public function equals($address) {
		if (get_class($address) != get_class($this)) {
			return false;
		}
		if ($address->getAddress() == $this->getAddress()) {
			return true;
		}
		return false;
	}

	/**
	 * Compare address to an other INETAddress. If $address is smaller in binary terms, it returns -1. If it is equal,
	 * it returns 0. If it is larger, it returns 1.
	 *
	 * @param INETAddress $address
	 *
	 * @throws INETAddressTypeException if the two addresses are not of the same type.
	 * @return int
	 */
	public function compareTo($address) {
		if (get_class($address) != get_class($this)) {
			throw new INETAddressTypeException($address, get_class($this));
		}
		return strcmp($address->getAsBinary(), $this->getAsBinary());
	}

	/**
	 * Get binary representation of the address
	 *
	 * @param int $mask
	 *
	 * @return string
	 */
	abstract public function getAsBinary($mask = null);
}
