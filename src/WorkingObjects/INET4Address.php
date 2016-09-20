<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

use Ixolit\Dislo\CDE\Exceptions\INETAddressFormatException;
use Ixolit\Dislo\CDE\Exceptions\INETInvalidMaskException;

/**
 * This class represents IPv4 addresses. This class was ported from the Opsbears Foundation library with
 * authorization.
 */
class INET4Address extends INETAddress {
	/**
	 * Multicast IP range
	 * @var INETRange
	 */
	protected static $mcrange;
	/**
	 * Private IP address ranges
	 * @var array of INETRange
	 */
	protected static $privateranges;
	/**
	 * Loopback IP range
	 * @var INETRange
	 */
	protected static $loopbackrange;
	/**
	 * Segment 1 of address.
	 * @var int
	 */
	protected $part1;
	/**
	 * Segment 2 of address.
	 * @var int
	 */
	protected $part2;
	/**
	 * Segment 3 of address.
	 * @var int
	 */
	protected $part3;
	/**
	 * Segment 4 of address.
	 * @var int
	 */
	protected $part4;

	/**
	 * Initialize this class with an address
	 *
	 * @param string $address default '0.0.0.0'
	 */
	public function __construct($address = '0.0.0.0') {
		parent::__construct($address);
	}

	/**
	 * Sets the address from a string
	 *
	 * @param string $address
	 *
	 * @throws INETAddressFormatException if the address is not a valid IPv4 address
	 * @return INET4Address
	 */
	public function setAddress($address) {
		if (!preg_match('/^(?P<part1>[0-9]{1,3}).(?P<part2>[0-9]{1,3}).'
			. '(?P<part3>[0-9]{1,3}).(?P<part4>[0-9]{1,3})$/',
			$address, $matches)
		) {
			throw new INETAddressFormatException($address);
		}
		if ((int)$matches['part1'] > 255 || (int)$matches['part2'] > 255 ||
			(int)$matches['part3'] > 255 || (int)$matches['part4'] > 255
		) {
			throw new INETAddressFormatException($address);
		}
		$this->part1 = (int)$matches['part1'];
		$this->part2 = (int)$matches['part2'];
		$this->part3 = (int)$matches['part3'];
		$this->part4 = (int)$matches['part4'];
		return $this;
	}

	/**
	 * Get the normalized version of the address.
	 * @return string
	 */
	public function getAddress() {
		return (string)$this->part1 . '.' . (string)$this->part2 . '.'
		. (string)$this->part3 . '.' . (string)$this->part4;
	}

	/**
	 * Returns the address as a binary representation, optionally masking the given number of bits.
	 *
	 * @param integer $mask default null
	 *
	 * @return string
	 *
	 * @throws INETInvalidMaskException
	 */
	public function getAsBinary($mask = null) {
		$binary = '';
		for ($i = 1; $i < 5; $i++) {
			$var = 'part' . $i;
			$binary .= str_pad(base_convert((string)$this->$var, 10, 2), 8, '0', STR_PAD_LEFT);
		}
		if (is_int($mask) && $mask > -1 && $mask < 33) {
			$binary = str_pad(substr($binary, 0, $mask), 32, '0', STR_PAD_RIGHT);
		} else {
			if (!is_null($mask)) {
				throw new INETInvalidMaskException($mask);
			}
		}
		return $binary;
	}

	/**
	 * Checks, if the address is a unicast or a broadcast address. (You can't tell from just the address.)
	 * @return bool
	 */
	public function isUnicast() {
		return !$this->isMulticast();
	}

	/**
	 * Checks, if the address is a multicast address.
	 *
	 * @return bool
	 */
	public function isMulticast() {
		return $this->isInRange(self::getMulticastRange());
	}

	/**
	 * Get the Multicast IP range
	 * @return INETRange
	 */
	protected static function getMulticastRange() {
		if (!self::$mcrange instanceof INETRange) {
			self::$mcrange = new INETRange(new INET4Address('224.0.0.0'), 4);
		}
		return self::$mcrange;
	}	/**
	 * Get the Multicast IP range
	 * @return array of INETRange
	 */
	protected static function getPrivateRanges() {
		if (!is_array(self::$privateranges)) {
			self::$privateranges = array(
				new INETRange(new INET4Address('10.0.0.0'), 8),
				new INETRange(new INET4Address('172.16.0.0'), 12),
				new INETRange(new INET4Address('192.168.0.0'), 16),
				new INETRange(new INET4Address('169.254.0.0'), 16),
				new INETRange(new INET4Address('224.0.0.0'), 24),
			);
		}
		return self::$privateranges;
	}

	/**
	 * Get the loopback IP range
	 * @return INETRange
	 */
	protected static function getLoopbackRange() {
		if (!self::$loopbackrange instanceof INETRange) {
			self::$loopbackrange = new INETRange(new INET4Address('127.0.0.0'), 8);
		}
		return self::$loopbackrange;
	}



	/**
	 * Checks, if the address is localhost
	 *
	 * @return bool
	 */
	public function isLoopback() {
		return $this->isInRange(self::getLoopbackRange());
	}

	/**
	 * Checks, if the address is globally routable.
	 *
	 * @return bool
	 */
	public function isGloballyRoutable() {
		foreach (self::getPrivateRanges() as $range) {
			if ($this->isInRange($range)) {
				return false;
			}
		}
		if ($this->isLoopback()) {
			return false;
		}
		return true;
	}
}
