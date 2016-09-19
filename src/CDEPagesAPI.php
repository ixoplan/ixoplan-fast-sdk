<?php

namespace Dislo\CDE\SDK;

use Dislo\CDE\SDK\Exceptions\CDEFeatureNotSupportedException;
use Dislo\CDE\SDK\Interfaces\PagesAPI;
use Dislo\CDE\SDK\WorkingObjects\Page;

class CDEPagesAPI implements PagesAPI {
	/**
	 * Get a list of all pages.
	 *
	 * @param string|null $vhost
	 * @param string|null $lang
	 * @param string|null $layout
	 * @param string|null $scheme
	 *
	 * @return Page[]
	 *
	 * @throws CDEFeatureNotSupportedException
	 */
	public function getAll($vhost = null, $lang = null, $layout = null, $scheme = null) {
		if (!\function_exists('getAllPages')) {
			throw new CDEFeatureNotSupportedException('getAllPages');
		}
		$pages = \getAllPages($vhost, $lang, $layout, $scheme);

		$result = [];
		foreach ($pages as $page) {
			$result[] = new Page($page->pageUrl, $page->pagePath);
		}

		return $result;
	}

	/**
	 * Returns a list of language codes supported on the current vhost. This is set up in vhost.ini.
	 *
	 * @return string[]
	 */
	public function getLanguages() {
		return $this->getLanguages();
	}
}