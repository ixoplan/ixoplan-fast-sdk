<?php

namespace Ixolit\Dislo\CDE\Exceptions;

class FileNotFoundException extends FilesystemException {
	/**
	 * @param string $filename
	 */
	public function __construct($filename) {
		parent::__construct('File or directory not found: ', $filename);
	}
}
