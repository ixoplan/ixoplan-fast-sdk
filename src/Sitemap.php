<?php

namespace Dislo\CDE\SDK;

use Dislo\CDE\SDK\Interfaces\PagesAPI;

class Sitemap {
	/**
	 * @var PagesAPI
	 */
	private $pagesApi;

	public function __construct(PagesAPI $pagesApi) {
		$this->pagesApi = $pagesApi;
	}

	/**
	 * Renders a sitemap.xml file for the given vhost. Always uses the default layout.
	 *
	 * @param null|string $vhost defaults to current vhost
	 *
	 * @return string
	 */
	function render($vhost = null, $languages = []) {
		$output = '';
		$output .= '<?xml version="1.0" encoding="UTF-8"?>';
		$output .= '<urlset
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
		xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		if (!$vhost) {
			$vhost = getVhost();
		}

		foreach (getAllLanguages() as $lang) {
			$output .= $this->renderSitemapFragment('http://www.' . $vhost . '/' . $lang, getAllPages($vhost, $lang,
				'default'));
		}

		$output .= '</urlset>';

		return $output;
	}

	/**
	 * Renders an XML sitemap fragment.
	 *
	 * @param string $path
	 * @param array  $data
	 *
	 * @return string
	 *
	 * @internal
	 */
	function renderSitemapFragment($path, $data) {
		$output = '';
		foreach ($data as $pageId => $pageData) {
			$output  .= '<url>';
			$output  .= '<loc>' . xml($pageData->pageUrl) . '</loc>';
			$output  .= '<changefreq>daily</changefreq>';
			$slashes  = substr_count($pageData->pageUrl, '/');
			$priority = round(1 - ($slashes - 2)/10, 2);
			$output  .= '<priority>' . xml($priority) . '</priority>';
			$output  .= '</url>';
		}
		return $output;
	}
}