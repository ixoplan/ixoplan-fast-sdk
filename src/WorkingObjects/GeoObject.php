<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

class GeoObject {
	const ISO_CODE_UNKNOWN = 'XX';

	/**
	 * @var string
	 */
	private $isoCode = 'XX';

	/**
	 * @var string
	 */
	private $name = 'Unknown';

	/**
	 * @param string $isoCode
	 * @param string $name
	 */
	public function __construct($isoCode, $name) {
		$this->isoCode = $isoCode;
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getIsoCode() {
		return $this->isoCode;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
}
