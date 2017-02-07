<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\CDE\CDEGeoLookupAPI;
use Ixolit\CDE\Exceptions\GeoLookupFailedException;
use Ixolit\Dislo\CDE\UnitTest\CDEUnitTest;

class CDEGeoLookupAPITest extends CDEUnitTest {
	public function testNonexistent() {
		//setup
		$api = new CDEGeoLookupAPI();
		//act
		try {
			$api->lookup('0.0.0.0');
			$this->fail();
		} catch (GeoLookupFailedException $e) {
			//pass
		}
	}

	public function testGoogleDNS() {
		//setup
		$api = new CDEGeoLookupAPI();
		//act
		$result = $api->lookup('8.8.8.8');
		//assert
		$this->assertEquals('US', $result->getCountry()->getIsoCode());
	}
}
