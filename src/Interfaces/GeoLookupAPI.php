<?php

namespace Dislo\CDE\SDK\Interfaces;

use Dislo\CDE\SDK\WorkingObjects\GeoLookupResponse;

interface GeoLookupAPI {
	/**
	 * @param string|null $ip
	 *
	 * @return GeoLookupResponse
	 */
	public function lookup($ip = null);
}
