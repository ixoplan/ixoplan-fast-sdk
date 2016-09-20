<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

class DirectoryFilesystemEntry extends FilesystemEntry  {
	public function __construct($name) {
		parent::__construct($name, self::TYPE_DIRECTORY);
	}

}