<?php

namespace Ixolit\Dislo\CDE;


/**
 * @deprecated use default dislo client with CDERequestClient
 */
class CDE extends \Ixolit\CDE\CDE {

	/**
	 * @return CDEDisloClient
	 */
	public static function getDisloClient() {
		return new CDEDisloClient();
	}
}