<?php

namespace Ixolit\Dislo\CDE\Exceptions;

class DirectoryExpectedException extends FilesystemException  {
	/**
	 * @param string $filename
	 */
	public function __construct($filename) {
		parent::__construct('Directory expected, but got file: ', $filename);
	}
}