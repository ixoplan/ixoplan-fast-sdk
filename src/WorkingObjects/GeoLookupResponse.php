<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

use Ixolit\Dislo\CDE\Exceptions\GeoInformationNotAvailableException;

class GeoLookupResponse {

	/**
	 * @var GeoCoordinates
	 */
	private $coordinates;

	/**
	 * @var GeoObject
	 */
	private $continent;

	/**
	 * @var GeoObject
	 */
	private $country;

	/**
	 * @var string
	 */
	private $timezone;

	/**
	 * @var string
	 */
	private $city;

	/**
	 * @var string
	 */
	private $ip;

	/**
	 * @param string              $ip
	 * @param GeoCoordinates|null $coordinates
	 * @param GeoObject|null      $continent
	 * @param GeoObject|null      $country
	 * @param string|null         $timezone
	 * @param string|null         $city
	 */
	public function __construct(
		$ip,
		GeoCoordinates $coordinates = null,
		GeoObject $continent = null,
		GeoObject $country = null,
		$timezone = null,
		$city = null
	) {
		$this->coordinates = $coordinates;
		$this->continent   = $continent;
		$this->country     = $country;
		$this->timezone    = $timezone;
		$this->city        = $city;
		$this->ip          = $ip;
	}

	/**
	 * @return string
	 */
	public function getIp() {
		return $this->ip;
	}

	/**
	 * @return GeoCoordinates
	 *
	 * @throws GeoInformationNotAvailableException
	 */
	public function getCoordinates() {
		if ($this->coordinates === null) {
			throw new GeoInformationNotAvailableException('coordinate', $this->ip);
		}
		return $this->coordinates;
	}

	/**
	 * @return GeoObject
	 *
	 * @throws GeoInformationNotAvailableException
	 */
	public function getContinent() {
		if ($this->continent === null) {
			throw new GeoInformationNotAvailableException('continent', $this->ip);
		}
		return $this->continent;
	}

	/**
	 * @return GeoObject
	 *
	 * @throws GeoInformationNotAvailableException
	 */
	public function getCountry() {
		if ($this->country === null) {
			throw new GeoInformationNotAvailableException('country', $this->ip);
		}
		return $this->country;
	}

	/**
	 * @return string
	 *
	 * @throws GeoInformationNotAvailableException
	 */
	public function getTimezone() {
		if ($this->timezone === null) {
			throw new GeoInformationNotAvailableException('timezone', $this->ip);
		}
		return $this->timezone;
	}

	/**
	 * @return string
	 *
	 * @throws GeoInformationNotAvailableException
	 */
	public function getCity() {
		if ($this->city === null) {
			throw new GeoInformationNotAvailableException('city', $this->ip);
		}
		return $this->city;
	}
}