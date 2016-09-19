<?php

namespace Dislo\CDE\SDK\Interfaces;

use Dislo\CDE\SDK\WorkingObjects\Page;

interface PagesAPI {
	/**
	 * Get a list of all pages.
	 *
	 * @param string|null $vhost
	 * @param string|null $lang
	 * @param string|null $layout
	 * @param string|null $scheme
	 *
	 * @return Page[]
	 */
	public function getAll($vhost = null, $lang = null, $layout = null, $scheme = null);

	/**
	 * Returns a list of language codes supported on the current vhost. This is set up in vhost.ini.
	 *
	 * @return string[]
	 */
	public function getLanguages();
}