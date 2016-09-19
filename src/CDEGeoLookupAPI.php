<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\CDEFeatureNotSupportedException;
use Ixolit\Dislo\CDE\Exceptions\GeoLookupFailedException;
use Ixolit\Dislo\CDE\Interfaces\GeoLookupAPI;
use Ixolit\Dislo\CDE\WorkingObjects\GeoCoordinates;
use Ixolit\Dislo\CDE\WorkingObjects\GeoLookupResponse;
use Ixolit\Dislo\CDE\WorkingObjects\GeoObject;

class CDEGeoLookupAPI implements GeoLookupAPI {
	/**
	 * {@inheritdoc}
	 */
	public function lookup($ip = null) {
		if (!\function_exists('geoInfo')) {
			throw new CDEFeatureNotSupportedException('geoInfo');
		}

		$geoInfo = \geoInfo($ip);
		if ($geoInfo === null) {
			throw new GeoLookupFailedException($ip);
		}

		$coordinates = (
			isset($geoInfo->location)?
			new GeoCoordinates($geoInfo->location->latitude, $geoInfo->location->longitude):
			null);
		$continent = (
			isset($geoInfo->continent)?
			new GeoObject($geoInfo->continent->iso_code, $geoInfo->continent->name):
			null);
		$country = (
			isset($geoInfo->country)?
			new GeoObject($geoInfo->country->iso_code, $geoInfo->country->name):
			null);
		$timezone = (
			isset($geoInfo->location) && !empty($geoInfo->location->timezone)?
			$geoInfo->location->timezone:
			null);
		$city = (isset($geoInfo->city) && !empty($geoInfo->city->name)?
			$geoInfo->city->name : null);

		return new GeoLookupResponse(
			$ip,
			$coordinates,
			$continent,
			$country,
			$timezone,
			$city
		);
	}
}
