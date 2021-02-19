<?php

namespace Ixolit\Dislo\CDE\Request;

use Ixolit\Dislo\Request\InvalidResponseData;
use Ixolit\Dislo\Request\RequestClient;
use Ixolit\Dislo\Request\RequestClientWithDevModeSupport;

/**
 * This client uses the CDE-internal API to talk to Dislo.
 */
class CDERequestClient implements RequestClient, RequestClientWithDevModeSupport {

	/**
	 * Enable/Disable the developer mode, which when enables might return different plans/billing methods etc. depending on the backend configuration
	 * @var bool
	 */
	private $devMode = false;

	/**
	 * @param bool $devMode
	 * @return $this
	 */
	public function setDevMode($devMode) {
		$this->devMode = (bool) $devMode;
		return $this;
	}

	/**
	 * @param string $uri
	 * @param array  $params
	 *
	 * @return array
	 *
	 * @throws InvalidResponseData
	 */
	public function request($uri, array $params) {
		$response = \apiCall('dislo', $uri . ($this->devMode ? '?devMode=1' : ''), \json_encode($params));

		$decodedBody = \json_decode($response->body, true);
		if (\json_last_error() == JSON_ERROR_NONE) {
			return $decodedBody;
		}

		throw new InvalidResponseData($response->body);
	}
}