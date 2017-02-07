<?php

// TODO: dislo

namespace Ixolit\Dislo\CDE\Request;

use Ixolit\Dislo\Request\InvalidResponseData;
use Ixolit\Dislo\Request\RequestClient;

/**
 * This client uses the CDE-internal API to talk to Dislo.
 */
class CDERequestClient implements RequestClient {

	/**
	 * @param string $uri
	 * @param array  $params
	 *
	 * @return array
	 *
	 * @throws InvalidResponseData
	 */
	public function request($uri, array $params) {
		$response = \apiCall('dislo', $uri, \json_encode($params));

		$decodedBody = \json_decode($response->body, true);
		if (\json_last_error() == JSON_ERROR_NONE) {
			return $decodedBody;
		}

		throw new InvalidResponseData($response->body);
	}
}