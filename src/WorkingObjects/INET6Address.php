<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

use Ixolit\Dislo\CDE\Exceptions\INETAddressFormatException;
use Ixolit\Dislo\CDE\Exceptions\INETInvalidMaskException;

/**
 * This class represents IPv6 addresses. This class was ported from the Opsbears Foundation library with
 * authorization.
 */
class INET6Address extends INETAddress {
	/**
	 * Globally routable unicast addresses
	 * @var INETRange
	 */
	protected static $globaladdresses;
	/**
	 * Unique Local Addresses
	 * @var INETRange
	 */
	protected static $ulaaddresses;
	/**
	 * Link-local addresses
	 * @var INETRange
	 */
	protected static $localaddresses;
	/**
	 * Loopback address
	 * @var INETRange
	 */
	protected static $loopbackaddresses;
	/**
	 * Exceptions from $globallyroutableaddr;
	 * @var array of INETRange
	 */
	protected static $globallyroutableexc;
	/**
	 * Globally routable addresses
	 * @var array of INETRange
	 */
	protected static $globallyroutableaddr;
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
	 * Segment 5 of address.
	 * @var int
	 */
	protected $part5;
	/**
	 * Segment 6 of address.
	 * @var int
	 */
	protected $part6;
	/**
	 * Segment 7 of address.
	 * @var int
	 */
	protected $part7;
	/**
	 * Segment 8 of address.
	 * @var int
	 */
	protected $part8;

	/**
	 * Initialize this class with an address
	 *
	 * @param string $address default '::'
	 */
	public function __construct($address = '::') {
		parent::__construct($address);
	}

	/**
	 * Get global unicast addresses
	 * @return INETRange
	 */
	protected static function getGlobalUnicastAddressRange() {
		if (!self::$globaladdresses instanceof INETRange) {
			self::$globaladdresses = new INETRange(new INET6Address('2000::'), 3);
		}
		return self::$globaladdresses;
	}

	/**
	 * Sets the address from a string
	 *
	 * @param string $address
	 *
	 * @throws INETAddressFormatException if the address is not a valid IPv6 address
	 * @return INET6Address
	 */
	public function setAddress($address) {
		$colons = preg_match_all('/:/', $address);
		if ($address == '::') {
			$newaddress = '0:0:0:0:0:0:0:0';
		} else {
			$replacement = '';
			for ($i = 1; $i < 9 - $colons; $i++) {
				if ($i > 1) {
					$replacement .= ':';
				}
				$replacement .= '0';
			}
			$replacement = ':' . $replacement . ':';
			if (preg_match('/^::/', $address)) {
				$replacement = '0' . $replacement;
			} else {
				if (preg_match('/::$/', $address)) {
					$replacement = $replacement . '0';
				}
			}
			$newaddress = str_replace('::', $replacement, $address);
		}
		if (!preg_match('/^(?P<part1>[a-fA-F0-9]{1,4}):(?P<part2>[a-fA-F0-9]{1,4}):'
			. '(?P<part3>[a-fA-F0-9]{1,4}):(?P<part4>[a-fA-F0-9]{1,4}):'
			. '(?P<part5>[a-fA-F0-9]{1,4}):(?P<part6>[a-fA-F0-9]{1,4}):'
			. '(?P<part7>[a-fA-F0-9]{1,4}):(?P<part8>[a-fA-F0-9]{1,4})$/D',
			$newaddress, $matches)
		) {
			throw new INETAddressFormatException($address);
		}
		for ($i = 1; $i < 9; $i++) {
			$var        = 'part' . $i;
			$this->$var = (int)base_convert($matches[$var], 16, 10);
		}
		return $this;
	}	/**
	 * Get link-local address range
	 * @return INETRange
	 */
	protected static function getLinkLocalAddressRange() {
		if (!self::$localaddresses instanceof INETRange) {
			self::$localaddresses = new INETRange(new INET6Address('fe80::'), 10);
		}
		return self::$localaddresses;
	}

	/**
	 * Get loopback address range
	 * @return INETRange
	 */
	protected static function getLoopbackAddressRange() {
		if (!self::$loopbackaddresses instanceof INETRange) {
			self::$loopbackaddresses = new INETRange(new INET6Address('::1'), 128);
		}
		return self::$loopbackaddresses;
	}

	/**
	 * Get unique local address range
	 * @return INETRange
	 */
	protected static function getUniqueLocalAddressRange() {
		if (!self::$ulaaddresses instanceof INETRange) {
			self::$ulaaddresses = new INETRange(new INET6Address('fc00::'), 7);
		}
		return self::$ulaaddresses;
	}





	/**
	 * Get the normalized version of the address.
	 * @return string
	 */
	public function getAddress() {
		$address = '';
		for ($i = 1; $i < 9; $i++) {
			if ($i > 1) {
				$address .= ':';
			}
			$var = 'part' . $i;
			$address .= base_convert((string)$this->$var, 10, 16);
		}
		return $address;
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
		for ($i = 1; $i < 9; $i++) {
			$var = 'part' . $i;
			$binary .= str_pad(base_convert($this->$var, 10, 2), 16, '0', STR_PAD_LEFT);
		}
		if (is_int($mask) && $mask > -1 && $mask < 129) {
			$binary = str_pad(substr($binary, 0, $mask), 128, '0', STR_PAD_RIGHT);
		} else {
			if (!is_null($mask)) {
				throw new INETInvalidMaskException($mask);
			}
		}
		return $binary;
	}

	/**
	 * Checks, if the address is a unicast address.
	 * @return bool
	 */
	public function isUnicast() {
		if ($this->isInRanges(array(
			self::getGlobalUnicastAddressRange(),
			self::getLinkLocalAddressRange(),
			self::getLoopbackAddressRange(),
			self::getUniqueLocalAddressRange(),
		))
		) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks, if the address is a multicast address.
	 * @return bool
	 */
	public function isMulticast() {
		if ($this->part1 > 65280) {
			//ff00::/12
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks, if the address is a loopback address.
	 * @return bool
	 */
	public function isLoopback() {
		if ($this->getAddress() == '0:0:0:0:0:0:0:1') {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks, if the address is globally routable.
	 * These addresses are NOT routable:
	 * <ul>
	 *    <li>::1/128 &ndash; localhost</li>
	 *    <li>fe80::/10 &ndash; Link-local autoconf addresses</li>
	 *    <li>fc00::/7 &ndash; ULA</li>
	 *    <li>2001:10::/28 &ndash; ORCHID (Overlay Routable Cryptographic Hash Identifiers)</li>
	 *    <li>2001:db8::/32 &ndash; Documentation prefix</li>
	 * </ul>
	 * These addresses ARE routable:
	 * <ul>
	 *    <li>2000::/3 &ndash; Global unicast</li>
	 *    <li>ff0e::/16 &ndash; Global permanent multicast</li>
	 *    <li>ff1e::/16 &ndash; Global transient multicast</li>
	 * </ul>
	 * @return bool
	 */
	public function isGloballyRoutable() {
		if (!is_array(self::$globallyroutableaddr)) {
			self::$globallyroutableexc  = array(
				new INETRange(new INET6Address('2001:10::'), 28),
				new INETRange(new INET6Address('2001:db8::'), 32),
			);
			self::$globallyroutableaddr = array(
				new INETRange(new INET6Address('2000::'), 3),
				new INETRange(new INET6Address('ff0e::'), 16),
				new INETRange(new INET6Address('ff1e::'), 16),
			);
		}
		if ($this->isInRanges(self::$globallyroutableexc)) {
			return false;
		} else {
			if ($this->isInRanges(self::$globallyroutableaddr)) {
				return true;
			} else {
				return false;
			}
		}
	}
}
