<?php


namespace Ixolit\Dislo\CDE\Interfaces;

/**
 * The sitemap renderer generates a Google sitemap.
 */
interface SitemapRenderer {
	/**
	 * Renders a sitemap.xml file for the given vhost. Always uses the default layout.
	 *
	 * @param null|string $vhost defaults to current vhost
	 * @param array       $languages
	 *
	 * @return string
	 */
	function render($vhost = null, $languages = []);
}