<?php


namespace Ixolit\Dislo\CDE\Exceptions;

abstract class FilesystemException extends \Exception implements CDEException {
	/**
	 * @var string
	 */
	private $filename;

	/**
	 * @param string $message
	 * @param string $filename
	 */
	public function __construct($message, $filename) {
		parent::__construct($message . $filename);

		$this->filename = $filename;
	}

	/**
	 * @return string
	 */
	public function getFilename() {
		return $this->filename;
	}
}