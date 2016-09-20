<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\CDEFeatureNotSupportedException;
use Ixolit\Dislo\CDE\Exceptions\DirectoryExpectedException;
use Ixolit\Dislo\CDE\Exceptions\FileNotFoundException;
use Ixolit\Dislo\CDE\Exceptions\UnexpectedFilesystemEntryException;
use Ixolit\Dislo\CDE\Interfaces\FilesystemAPI;
use Ixolit\Dislo\CDE\WorkingObjects\DirectoryFilesystemEntry;
use Ixolit\Dislo\CDE\WorkingObjects\FileFilesystemEntry;
use Ixolit\Dislo\CDE\WorkingObjects\FilesystemEntry;

class CDEFilesystemAPI implements FilesystemAPI {
	/**
	 * {@inheritdoc}
	 */
	public function exists($path) {
		if (!\function_exists('getPathInfo')) {
			throw new CDEFeatureNotSupportedException('getPathInfo');
		}
		return (\getPathInfo($path) === null);
	}

	/**
	 * @param string    $name
	 * @param \stdClass $entry
	 *
	 * @return DirectoryFilesystemEntry|FileFilesystemEntry
	 * @throws UnexpectedFilesystemEntryException
	 */
	private function entryToObject($name, $entry) {
		switch ($entry->type) {
			case 'file':
				$modified = new \DateTime();
				$modified->setTimestamp($entry->modified);
				return new FileFilesystemEntry($name, $entry->size, $modified);
				break;
			case 'directory':
				return new DirectoryFilesystemEntry($name);
				break;
			default:
				throw new UnexpectedFilesystemEntryException($name, $entry->type);
				break;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function pathInfo($path) {
		if (!\function_exists('getPathInfo')) {
			throw new CDEFeatureNotSupportedException('getPathInfo');
		}
		$entry = getPathInfo($path);
		if ($entry === null) {
			throw new FileNotFoundException($path);
		}
		return $this->entryToObject($path, $entry);
	}

	/**
	 * {@inheritdoc}
	 */
	public function listDirectory($directory) {
		if (!\function_exists('getPathInfo')) {
			throw new CDEFeatureNotSupportedException('getPathInfo');
		}
		if (!\function_exists('listDirectory')) {
			throw new CDEFeatureNotSupportedException('listDirectory');
		}
		if ($this->pathInfo($directory)->getType() == FilesystemEntry::TYPE_FILE) {
			throw new DirectoryExpectedException($directory);
		}
		$result = [];
		foreach (listDirectory($directory) as $name => $entry) {
			$result[] = $this->entryToObject($name, $entry);
		}
		return $result;
	}
}
