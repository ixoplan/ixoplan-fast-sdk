<?php

namespace Ixolit\Dislo\CDE\Interfaces;

use Ixolit\Dislo\CDE\Exceptions\DirectoryExpectedException;
use Ixolit\Dislo\CDE\Exceptions\FileNotFoundException;
use Ixolit\Dislo\CDE\WorkingObjects\FilesystemEntry;

/**
 * The filesystem API provides basic, read only access to the filesystem in the CDE.
 */
interface FilesystemAPI {
	/**
	 * Check if a file exists.
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public function exists($path);

	/**
	 * Get information about one file.
	 *
	 * @param string $path
	 *
	 * @return FilesystemEntry
	 *
	 * @throws FileNotFoundException if $path does not exist.
	 */
	public function pathInfo($path);

	/**
	 * List contents of a directory.
	 *
	 * @param string $directory
	 *
	 * @return FilesystemEntry[]
	 *
	 * @throws FileNotFoundException      if $directory does not exist.
	 * @throws DirectoryExpectedException if $directory is a file
	 */
	public function listDirectory($directory);
}
