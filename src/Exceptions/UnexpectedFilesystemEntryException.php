<?php

namespace Ixolit\Dislo\CDE\Exceptions;

class UnexpectedFilesystemEntryException extends FilesystemException  {
	/**
	 * @var string
	 */
	private $type;

	/**
	 * @param string $filename
	 * @param string $type
	 */
	public function __construct($filename, $type) {
		parent::__construct('Unexpected filesystem entry type ' . $type . ' in entry ', $filename);
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
}