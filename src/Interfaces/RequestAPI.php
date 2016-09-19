<?php

namespace Dislo\CDE\SDK\Interfaces;

interface RequestAPI {
	/**
	 * Returns the resolved, normalized vhost name for the current request.
	 *
	 * @return string
	 */
	public function getVhost();
}
