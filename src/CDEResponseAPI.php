<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\CDEFeatureNotSupportedException;
use Ixolit\Dislo\CDE\Exceptions\InvalidStatusCodeException;
use Ixolit\Dislo\CDE\Interfaces\ResponseAPI;

class CDEResponseAPI implements ResponseAPI {
	/**
	 * {@inheritdoc}
	 */
	public function redirectTo($location, $permanent = false) {
		if (!\function_exists('redirectTo')) {
			throw new CDEFeatureNotSupportedException('redirectTo');
		}
		redirectTo($location, $permanent);
	}

	/**
	 * {@inheritdoc}
	 */
	public function redirectToPage($page, $lang = null, $permanent = false) {
		if (!\function_exists('redirectToPage')) {
			throw new CDEFeatureNotSupportedException('redirectToPage');
		}
		redirectToPage($page, $lang, $permanent);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setContentType($contentType) {
		if (!\function_exists('setContentType')) {
			throw new CDEFeatureNotSupportedException('setContentType');
		}
		$this->setContentType($contentType);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setStatusCode($statusCode) {
		if (!\function_exists('setStatusCode')) {
			throw new CDEFeatureNotSupportedException('setStatusCode');
		}
		if (!setStatusCode($statusCode)) {
			throw new InvalidStatusCodeException($statusCode);
		}
	}
}