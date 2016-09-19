<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\CDEFeatureNotSupportedException;
use Ixolit\Dislo\CDE\Interfaces\RequestAPI;

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