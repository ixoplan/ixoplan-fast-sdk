<?php

namespace Ixolit\Dislo\CDE\Exceptions;

class MetadataNotAvailableException extends \Exception implements CDEException {
	private $meta;

	/**
	 * @param string $meta
	 */
	public function __construct($meta) {
		parent::__construct('Metadata entry not available: ' . $meta);
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getMeta() {
		return $this->meta;
	}
}