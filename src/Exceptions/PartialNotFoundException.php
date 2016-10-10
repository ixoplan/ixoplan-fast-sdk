<?php

namespace Ixolit\Dislo\CDE\Exceptions;

class PartialNotFoundException extends \Exception implements CDEException {
	/**
	 * @var string
	 */
	private $partial;

	public function __construct($partial) {
		parent::__construct('Partial not found: ' . $partial);

		$this->partial = $partial;
	}

	/**
	 * @return string
	 */
	public function getPartial() {
		return $this->partial;
	}
}