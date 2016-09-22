<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

abstract class FilesystemEntry {
	const TYPE_FILE = 'file';
	const TYPE_DIRECTORY = 'directory';

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param string $name
	 * @param string $type
	 */
	public function __construct($name, $type) {
		$this->type      = $type;
		$this->name      = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	public function __toString() {
		return $this->name;
	}
}