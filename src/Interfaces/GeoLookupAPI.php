<?php

namespace Ixolit\Dislo\CDE\Interfaces;

use Ixolit\Dislo\CDE\Exceptions\GeoLookupFailedException;
use Ixolit\Dislo\CDE\WorkingObjects\GeoLookupResponse;

interface GeoLookupAPI {
	/**
	 * @param string|null $ip
	 *
	 * @return GeoLookupResponse
	 *
	 * @throws GeoLookupFailedException
	 */
	public function lookup($ip = null);
}
