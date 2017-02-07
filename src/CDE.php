<?php

namespace Ixolit\Dislo\CDE;


class CDE extends \Ixolit\CDE\CDE {

	/**
	 * @return CDEDisloClient
	 */
	public static function getDisloClient() {
		return new CDEDisloClient();
	}
}