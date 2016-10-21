<?php

namespace Ixolit\Dislo\CDE\Form;

use Ixolit\Dislo\CDE\Exceptions\CookieNotSetException;
use Ixolit\Dislo\CDE\Interfaces\RequestAPI;
use Ixolit\Dislo\CDE\Interfaces\ResponseAPI;

class CookieCSRFTokenProvider implements CSRFTokenProvider {
	/**
	 * @var RequestAPI
	 */
	private $requestAPI;
	/**
	 * @var ResponseAPI
	 */
	private $responseAPI;

	public function __construct(
		RequestAPI $requestAPI,
		ResponseAPI $responseAPI
	) {
		$this->requestAPI = $requestAPI;
		$this->responseAPI = $responseAPI;

		$this->responseAPI->setCookie('csrf-token', $this->getCSRFToken());
	}

	public function getCSRFToken() {
		try {
			$token = $this->requestAPI->getCookie('csrf-token')->getValue();
		} catch (CookieNotSetException $e) {
			$token = \md5(\mt_rand(PHP_INT_MIN, PHP_INT_MAX));
		}
		return $token;
	}
}