<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\INETAddressFormatException;
use Ixolit\Dislo\CDE\Exceptions\INETAddressTypeException;
use Ixolit\Dislo\CDE\Exceptions\INETInvalidMaskException;
use Ixolit\Dislo\CDE\WorkingObjects\INET4Address;
use Ixolit\Dislo\CDE\WorkingObjects\INET6Address;

/**
 * This class was ported from the Opsbears Foundation library with authorization.
 */
class INET6AddressTest extends UnitTest\CDEUnitTest {
	/**
	 * This function tests basic functionality with valid IPv6 addresses.
	 */
	public function testValidAddress() {
		$o = new INET6Address('2001:950:0:16:0:0:0:0');
		$this->assertEquals('2001:950:0:16:0:0:0:0', $o->__toString(),
			'INET6Address does not return the input IP address.');
		$o = new INET6Address('2001:950:0:16::');
		$this->assertEquals('2001:950:0:16:0:0:0:0', $o->__toString(),
			'INET6Address does not return the canonialized IP address.');
		$o = new INET6Address('2001:950::0:16');
		$this->assertEquals('2001:950:0:0:0:0:0:16', $o->__toString(),
			'INET6Address does not return the canonialized IP address.');
		$o = new INET6Address('::0:16');
		$this->assertEquals('0:0:0:0:0:0:0:16', $o->__toString(),
			'INET6Address does not return the canonialized IP address.');
		$o = new INET6Address('::');
		$this->assertEquals('0:0:0:0:0:0:0:0', $o->__toString(),
			'INET6Address does not return the canonialized IP address.');
	}

	/**
	 * This function tests the getAsBinary function
	 */
	public function testGetAsBinary() {
		$o = new INET6Address('2001:950:0:16:0:0:0:2');
		$this->assertEquals(
			'0010000000000001000010010101000000000000000000000000000000010110000000000000000000000000' .
			'0000000000000000000000000000000000000010',
			$o->getAsBinary(),
			'INET6Address does not return the valid binary representation.');
		$this->assertEquals(
			'0010000000000001000010010101000000000000000000000000000000010110000000000000000000000000' .
			'0000000000000000000000000000000000000000',
			$o->getAsBinary(64),
			'INET6Address does not mask properly.');
	}

	/**
	 * Test binary masking with an invalid type.
	 */
	public function testTypeBinaryMask() {
		$o = new INET6Address('2001:950:0:16:0:0:0:2');
		try {
			$o->getAsBinary('129');
			$this->fail('Invalid type IP mask does not throw an exception!');
		} catch (INETInvalidMaskException $e) {
			//pass
		}
	}

	/**
	 * Test binary masking with a too small number.
	 */
	public function testTooSmallBinaryMask() {
		$o = new INET6Address('2001:950:0:16:0:0:0:2');
		try {
			$o->getAsBinary(-1);
			$this->fail('Too small IP mask does not throw an exception!');
		} catch (INETInvalidMaskException $e) {

		}
	}

	/**
	 * Test binary masking with a too large number.
	 */
	public function testTooLargeBinaryMask() {
		$o = new INET6Address('2001:950:0:16:0:0:0:2');
		try {
			$o->getAsBinary(129);
			$this->fail('Too large IP mask does not throw an exception!');
		} catch (INETInvalidMaskException $e) {

		}
	}

	/**
	 * Tests, if invalid IPv6 addresses get kicked out.
	 */
	public function testInvalidAddress() {
		try {
			$o = new INET6Address('2001:950:0:16:0:0:0:G');
			$this->fail('Invalid IP address did not result in an exception!');
		} catch (INETAddressFormatException $e) {

		}
	}

	/**
	 * Tests, if invalid format IPv6 addresses get kicked out.
	 */
	public function testInvalidFormatAddress() {
		try {
			$o = new INET6Address('2001:950:0:16:0:0:0:00001');
			$this->fail('Invalid format IP address did not result in an exception!');
		} catch (INETAddressFormatException $e) {

		}
	}

	/**
	 * Tests the functionality of equals()
	 */
	public function testEquals() {
		$o = new INET6Address('2001:950:0:16:0:0:0:2');
		$this->assertEquals(true, $o->equals($o));
		$o2 = new INET6Address('2001:950:0:16:0:0:0:2');
		$this->assertEquals(true, $o->equals($o2));
		$o3 = new INET6Address('2001:950:0:16:0:0:0:3');
		$this->assertEquals(false, $o->equals($o3));
		$o4 = new INET4Address('192.168.1.2');
		$this->assertEquals(false, $o->equals($o3));
	}

	/**
	 * Tests the functionality of compareTo()
	 */
	public function testCompareTo() {
		$o = new INET6Address('2001:950:0:16:0:0:0:2');
		$this->assertEquals(0, $o->compareTo($o));
		$o2 = new INET6Address('2001:950:0:16:0:0:0:2');
		$this->assertEquals(0, $o->compareTo($o2));
		$o3 = new INET6Address('2001:950:0:16:0:0:0:3');
		$this->assertEquals(1, $o->compareTo($o3));
		$o4 = new INET6Address('2001:950:0:16:0:0:0:1');
		$this->assertEquals(-1, $o->compareTo($o4));
	}

	/**
	 * Tests compareTo with an INET4Address
	 */
	public function testInvalidCompareTo() {
		$o = new INET6Address('2001:950:0:16:0:0:0:2');
		$o2 = new INET4Address('192.168.1.1');
		try {
			$o->compareTo($o2);
			$this->fail('Comparing two different type INETAddress objects does not result in an exception!');
		} catch (INETAddressTypeException $e) {

		}
	}

	/**
	 * Tests, that unicast addresses are properly detected.
	 */
	public function testUnicast() {
		$address = new INET6Address('2001:950:0:16::');
		$this->assertEquals(true, $address->isUnicast());
		$address = new INET6Address('fe80::');
		$this->assertEquals(true, $address->isUnicast());
		$address = new INET6Address('ff02::1');
		$this->assertEquals(false, $address->isUnicast());
		$address = new INET6Address('::1');
		$this->assertEquals(true, $address->isUnicast());
		$address = new INET6Address('fc00::1');
		$this->assertEquals(true, $address->isUnicast());
	}

	/**
	 * Tests, that multicast addresses are properly detected.
	 */
	public function testMulticast() {
		$address = new INET6Address('2001:950:0:16::');
		$this->assertEquals(false, $address->isMulticast());
		$address = new INET6Address('fe80::');
		$this->assertEquals(false, $address->isMulticast());
		$address = new INET6Address('ff02::1');
		$this->assertEquals(true, $address->isMulticast());
	}

	/**
	 * Tests, that loopback addresses are properly detected.
	 */
	public function testLoopback() {
		$address = new INET6Address('::');
		$this->assertEquals(false, $address->isLoopback());
		$address = new INET6Address('::1');
		$this->assertEquals(true, $address->isLoopback());
		$address = new INET6Address('::2');
		$this->assertEquals(false, $address->isLoopback());
	}

	/**
	 * This function tests for the following globally routable / unroutable prefixes:
	 * These prefixes are NOT routable:
	 *
	 * <ul>
	 *	<li>::1/128 &ndash; localhost</li>
	 *	<li>fe80::/10 &ndash; Link-local autoconf addresses</li>
	 *	<li>fc00::/7 &ndash; ULA</li>
	 *	<li>2001:10::/28 &ndash; ORCHID (Overlay Routable Cryptographic Hash Identifiers)</li>
	 *	<li>2001:db8::/32 &ndash; Documentation prefix</li>
	 * </ul>
	 * These addresses ARE routable:
	 * <ul>
	 *	<li>2000::/3 &ndash; Global unicast</li>
	 *	<li>ff0e::/16 &ndash; Global permanent multicast</li>
	 *	<li>ff1e::/16 &ndash; Global transient multicast</li>
	 * </ul>
	 */
	public function testGloballyRoutable() {
		$addresses = array(
			'::1' => false,
			'fe80::' => false,
			'fc00::' => false,
			'2001:10::' => false,
			'2001:db8::' => false,
			'2000::' => true,
			'ff0e::' => true,
			'ff1e::' => true,
		);
		foreach ($addresses as $address => $result) {
			$addr = new INET6Address($address);
			$this->assertEquals($result, $addr->isGloballyRoutable(), 'Address ' . $address . ' '
				. ($result==true?'should':'should not') . ' be globally routable!');
		}
	}
}
