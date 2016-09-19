<?php

namespace Dislo\CDE\SDK;

use Dislo\CDE\SDK\Exceptions\CDEFeatureNotSupportedException;
use Dislo\CDE\SDK\Interfaces\RequestAPI;

class CDERequestAPI implements RequestAPI  {
	/**
	 * {@inheritdoc}
	 */
	public function getVhost() {
		if (!function_exists('getVhost')) {
			throw new CDEFeatureNotSupportedException('getVhost');
		}
		return getVhost();
	}
}