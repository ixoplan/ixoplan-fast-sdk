<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\CDE\Exceptions\INETAddressTypeException;
use Ixolit\Dislo\CDE\UnitTest\CDEUnitTest;
use Ixolit\CDE\WorkingObjects\INET4Address;
use Ixolit\CDE\WorkingObjects\INET6Address;
use Ixolit\CDE\WorkingObjects\INETRange;

/**
 * This class was ported from the Opsbears Foundation library with authorization.
 */
class INETRangeTest extends CDEUnitTest {
	/**
	 * Tests, that range assignments don't result in exceptions.
	 */
	public function testValidRangeAssignment() {
		$range = new INETRange(new INET4Address('192.168.0.0'), 24);
		$range = new INETRange(new INET6Address('fe80::'), 10);
	}

	/**
	 * Tests, that IPv4 ranges actually work.
	 */
	public function testIPv4InRange() {
		$range = new INETRange(new INET4Address('192.168.2.0'), 24);
		$a1 = new INET4Address('192.168.1.1');
		$a2 = new INET4Address('192.168.2.1');
		$a3 = new INET4Address('192.168.3.1');
		$this->assertEquals(false, $a1->isInRange($range));
		$this->assertEquals(false, $range->containsAddress($a1));
		$this->assertEquals(true, $a2->isInRange($range));
		$this->assertEquals(true, $range->containsAddress($a2));
		$this->assertEquals(false, $a3->isInRange($range));
		$this->assertEquals(false, $range->containsAddress($a3));
	}

	/**
	 * Tests, that IPv6 ranges actually work.
	 */
	public function testIPv6InRange() {
		$range = new INETRange(new INET6Address('fe80::'), 10);
		$a1 = new INET6Address('fe7F::');
		$a2 = new INET6Address('fe80::1');
		$a3 = new INET6Address('fec0::');
		$this->assertEquals(false, $a1->isInRange($range));
		$this->assertEquals(false, $range->containsAddress($a1));
		$this->assertEquals(true, $a2->isInRange($range));
		$this->assertEquals(true, $range->containsAddress($a2));
		$this->assertEquals(false, $a3->isInRange($range));
		$this->assertEquals(false, $range->containsAddress($a3));
	}

	/**
	 * Tests, that IPv4 address ranges and IPv6 addresses are incompatible.
	 */
	public function testAddressIPv4Compability() {
		$range = new INETRange(new INET4Address('192.168.2.0'), 24);
		try {
			$range->containsAddress(new INET6Address('fe80::'));
			$this->fail('Checking an IPv6 address in an IPv4 range didn\'t result in an error');
		} catch (INETAddressTypeException $e) {

		}
	}

	/**
	 * Tests, that IPv6 address ranges and IPv4 addresses are incompatible.
	 */
	public function testAddressIPv6Compability() {
		$range = new INETRange(new INET6Address('fe80::'), 10);
		try {
			$range->containsAddress(new INET4Address('192.168.2.0'));
			$this->fail('Checking an IPv4 address in an IPv6 range didn\'t result in an error');
		} catch (INETAddressTypeException $e) {

		}
	}
}
