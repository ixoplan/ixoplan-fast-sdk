<?php

namespace Ixolit\Dislo\CDE\Interfaces;

use Ixolit\Dislo\CDE\Exceptions\MetadataNotAvailableException;

interface MetaAPI {
	/**
	 * Returns a given metadata value for the given page in the given language.
	 *
	 * @param string      $name
	 * @param string|null $language
	 * @param string|null $pagePath
	 * @param string|null $layout
	 *
	 * @return string
	 *
	 * @throws MetadataNotAvailableException
	 */
	public function getMeta($name, $language = null, $pagePath = null, $layout = null);

	/**
	 * Returns all metadata values for the given page in the given language.
	 *
	 * @param string|null $language
	 * @param string|null $pagePath
	 * @param string|null $layout
	 *
	 * @return string[]
	 *
	 * @throws MetadataNotAvailableException
	 */
	public function getAllMeta($language = null, $pagePath = null, $layout = null);

	/**
	 * Overwrite metadata value for the current page render.
	 *
	 * @param string      $name
	 * @param string      $value
	 * @param string|null $language
	 */
	public function setMeta($name, $value, $language = null);
}
