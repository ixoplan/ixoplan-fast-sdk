<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

class FileFilesystemEntry extends FilesystemEntry  {
	/**
	 * @var int
	 */
	private $size;
	/**
	 * @var \DateTime
	 */
	private $modified;

	public function __construct($name, $size, \DateTime $modified) {
		parent::__construct($name, self::TYPE_FILE);
		$this->size = $size;
		$this->modified = $modified;
	}

	/**
	 * @return int
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @return \DateTime
	 */
	public function getModified() {
		return $this->modified;
	}
}
