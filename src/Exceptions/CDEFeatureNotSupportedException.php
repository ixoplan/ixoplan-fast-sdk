<?php

namespace Dislo\CDE\SDK\Exceptions;

class CDEFeatureNotSupportedException extends \Exception implements CDEException {
	/**
	 * @var string
	 */
	private $feature;

	/**
	 * @param string $feature
	 */
	public function __construct($feature) {
		parent::__construct('CDE feature not supported: ' . $feature);
		$this->feature = $feature;
	}

	/**
	 * @return string
	 */
	public function getFeature() {
		return $this->feature;
	}
}