<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\CDE\Exceptions\INETAddressFormatException;
use Ixolit\CDE\Exceptions\INETAddressTypeException;
use Ixolit\CDE\Exceptions\INETInvalidMaskException;
use Ixolit\CDE\WorkingObjects\INET4Address;
use Ixolit\CDE\WorkingObjects\INET6Address;

/**
 * This class was ported from the Opsbears Foundation library with authorization.
 */
class INET4AddressTest extends \Ixolit\Dislo\CDE\UnitTest\CDEUnitTest {
	/**
	 * This function tests basic functionality with valid IPv4 addresses.
	 */
	public function testValidAddress() {
		$o = new INET4Address('192.168.1.1');
		$this->assertEquals('192.168.1.1', $o->__toString(), 'INET4Address does not return the input IP address.');
		$o = new INET4Address('192.168.001.001');
		$this->assertEquals('192.168.1.1', $o->__toString(),
			'INET4Address does not return the canonialized IP address.');
	}

	/**
	 * This function tests the getAsBinary function
	 */
	public function testGetAsBinary() {
		$o = new INET4Address('192.168.1.1');
		$this->assertEquals('11000000101010000000000100000001', $o->getAsBinary(),
			'INET4Address does not return the valid binary representation.');
		$this->assertEquals('11000000101010000000000100000000', $o->getAsBinary(24),
			'INET4Address does not mask properly.');
	}

	/**
	 * Test binary masking with an invalid type.
	 */
	public function testTypeBinaryMask() {
		$o = new INET4Address('192.168.1.1');
		try {
			$o->getAsBinary('24');
			$this->fail('Invalid type IP mask does not throw an exception!');
		} catch (INETInvalidMaskException $e) {

		}
	}

	/**
	 * Test binary masking with a too small number.
	 */
	public function testTooSmallBinaryMask() {
		$o = new INET4Address('192.168.1.1');
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
		$o = new INET4Address('192.168.1.1');
		try {
			$o->getAsBinary(33);
			$this->fail('Too large IP mask does not throw an exception!');
		} catch (INETInvalidMaskException $e) {
		}
	}

	/**
	 * Tests, if invalid IPv4 addresses get kicked out.
	 */
	public function testInvalidAddress() {
		try {
			$o = new INET4Address('192.168.1.256');
			$this->fail('Invalid IP address did not result in an exception!');
		} catch (INETAddressFormatException $e) {
		}
	}

	/**
	 * Tests, if invalid format IPv4 addresses get kicked out.
	 */
	public function testInvalidFormatAddress() {
		try {
			$o = new INET4Address('192.168.1.2557');
			$this->fail('Invalid format IP address did not result in an exception!');
		} catch (INETAddressFormatException $e) {
		}
	}

	/**
	 * Tests the functionality of equals()
	 */
	public function testEquals() {
		$o = new INET4Address('192.168.1.1');
		$this->assertEquals(true, $o->equals($o));
		$o2 = new INET4Address('192.168.1.1');
		$this->assertEquals(true, $o->equals($o2));
		$o3 = new INET4Address('192.168.1.2');
		$this->assertEquals(false, $o->equals($o3));
		$o4 = new INET6Address('ff80::');
		$this->assertEquals(false, $o->equals($o4));
	}

	/**
	 * Tests the functionality of compareTo()
	 */
	public function testCompareTo() {
		$o = new INET4Address('192.168.1.2');
		$this->assertEquals(0, $o->compareTo($o));
		$o2 = new INET4Address('192.168.1.2');
		$this->assertEquals(0, $o->compareTo($o2));
		$o3 = new INET4Address('192.168.1.3');
		$this->assertEquals(1, $o->compareTo($o3));
		$o4 = new INET4Address('192.168.1.1');
		$this->assertEquals(-1, $o->compareTo($o4));
	}

	/**
	 * Tests compareTo with an INET6Address
	 */
	public function testInvalidCompareTo() {
		$o = new INET4Address('192.168.1.1');
		$o2 = new INET6Address('2001:950:0:16:0:0:0:2');
		try {
			$o->compareTo($o2);
			$this->fail('Comparing two different type INETAddress objects does not result in an exception!');
		} catch (INETAddressTypeException $e) {

		}
	}

	/**
	 * Tests, that unicast/broadcast addresses are properly detected.
	 */
	public function testUnicastOrBroadcast() {
		$address = new INET4Address('192.168.1.1');
		$this->assertEquals(true, $address->isUnicast());
		$address = new INET4Address('224.0.0.1');
		$this->assertEquals(false, $address->isUnicast());
	}

	/**
	 * Tests, that multicast addresses are properly detected.
	 */
	public function testMulticast() {
		$address = new INET4Address('192.168.1.1');
		$this->assertEquals(false, $address->isMulticast());
		$address = new INET4Address('224.0.0.1');
		$this->assertEquals(true, $address->isMulticast());
	}

	/**
	 * Tests, that loopback addresses are properly detected.
	 */
	public function testLoopback() {
		$address = new INET4Address('126.255.255.255');
		$this->assertEquals(false, $address->isLoopback());
		$address = new INET4Address('127.0.2.1');
		$this->assertEquals(true, $address->isLoopback());
		$address = new INET4Address('128.0.0.0');
		$this->assertEquals(false, $address->isLoopback());
	}

	/**
	 * This function tests, if globally routable address detection works.
	 */
	public function testGloballyRoutable() {
		$addresses = array(
			'127.0.0.1' => false,
			'192.168.1.1' => false,
			'172.24.1.1' => false,
			'10.0.0.1' => false,
			'169.254.0.1' => false,
			'224.0.0.0' => false,
			'80.77.120.0' => true,
			'224.0.1.0' => true,
			'224.0.2.0' => true,
		);
		foreach ($addresses as $address => $result) {
			$addr = new INET4Address($address);
			$this->assertEquals($result, $addr->isGloballyRoutable(), 'Address ' . $address . ' '
				. ($result==true?'should':'should not') . ' be globally routable!');
		}
	}
}
