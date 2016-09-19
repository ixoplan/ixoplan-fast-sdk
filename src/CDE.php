<?php

namespace Ixolit\Dislo\CDE;

/**
 * This class gives static access to the default API implementations.
 */
class CDE {
	private function __construct() {}

	/**
	 * @return Interfaces\KVSAPI
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
	 * @return Interfaces\GeoLookupAPI
	 */
	public static function getGeoAPI() {
		return new CDEGeoLookupAPI();
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
