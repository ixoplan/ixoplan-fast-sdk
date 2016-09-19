<?php

namespace Dislo\CDE\SDK;

use Dislo\CDE\SDK\Interfaces\KVSAPI;

class CDE {
	/**
	 * @return KVSAPI
	 */
	public static function getKVSAPI() {
		return new CDEKVSAPI();
	}

	/**
	 * @return Interfaces\PagesAPI
	 */
	public static function getPagesAPI() {
		return new CDEPagesAPI();
	}

	/**
	 * @return Interfaces\RequestAPI
	 */
	public static function getRequestAPI() {
		return new CDERequestAPI();
	}

	/**
	 * @return Interfaces\SitemapRenderer
	 */
	public static function getSitemapRenderer() {
		return new SitemapRenderer(
			self::getPagesAPI(),
			self::getRequestAPI()
		);
	}
}