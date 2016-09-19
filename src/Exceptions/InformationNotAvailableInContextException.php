<?php

namespace Ixolit\Dislo\CDE\Exceptions;

class InformationNotAvailableInContextException extends \Exception implements CDEException  {
	/**
	 * @var string
	 */
	private $type;

	public function __construct($type) {
		parent::__construct(ucfirst($type) . ' information is not available in current context.');
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
}