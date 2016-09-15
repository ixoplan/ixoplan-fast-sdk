<?php

namespace Dislo\CDE\SDK;

use Dislo\CDE\SDK\Interfaces\PagesAPI;
use Dislo\CDE\SDK\WorkingObjects\Page;

class CDEAPI implements PagesAPI {
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
	public function getAll($vhost = null, $lang = null, $layout = null, $scheme = null) {
		$pages = getAllPages($vhost, $lang, $layout, $scheme);

		$result = [];
		foreach ($pages as $page) {
			$result[] = new Page($page->pageUrl, $page->pagePath);
		}

		return $result;
	}
}