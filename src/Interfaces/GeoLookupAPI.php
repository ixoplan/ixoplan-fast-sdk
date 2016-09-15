<?php

namespace Dislo\CDE\SDK\Interfaces;

interface GeoLookupAPI {
	/**
	 * @param string|null $ip
	 *
	 * @return GeoLookupResponse
	 */
	public function lookup($ip = null);
}