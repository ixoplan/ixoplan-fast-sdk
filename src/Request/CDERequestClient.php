<?php

namespace Ixolit\Dislo\CDE\Request;

use Ixolit\CDE\Exceptions\CDEFeatureNotSupportedException;
use Ixolit\CDE\Exceptions\KVSKeyNotFoundException;
use Ixolit\CDE\Interfaces\KVSAPI;
use Ixolit\Dislo\CDE\Context\Page;
use Ixolit\Dislo\Request\InvalidResponseData;
use Ixolit\Dislo\Request\RequestClient;

/**
 * This client uses the CDE-internal API to talk to Dislo.
 */
class CDERequestClient implements RequestClient {

    const API_URI_PACKAGE_LIST = '/frontend/subscription/getPackages';
    const API_URI_MISC_REDIRECTOR_CONFIGURATION = '/frontend/misc/getRedirectorConfiguration';

    /** @var KVSAPI */
    private $kvsApi;

    /** @var bool */
    private $useKvs;

    /**
     * CDERequestClient constructor.
     *
     * Checks for existing method apiCall().
     *
     * @param KVSAPI|null $kvsApi
     * @param bool        $useKvs
     *
     * @throws CDEFeatureNotSupportedException
     */
    public function __construct(KVSAPI $kvsApi = null, $useKvs  = true) {
        if (!\function_exists('\\apiCall')) {
            throw new CDEFeatureNotSupportedException('apiCall');
        }

        $this->kvsApi = $kvsApi ? $kvsApi : Page::kvsAPI();
        $this->useKvs = $useKvs;
    }

    /**
     * @return KVSAPI
     */
    private function getKvsApi() {
        return $this->kvsApi;
    }

    /**
     * @param bool $useKvs
     *
     * @return $this
     */
    public function setUseKvs($useKvs) {
        $this->useKvs = $useKvs;

        return $this;
    }

    /**
     * @return bool
     */
    private function usesKvs() {
        return $this->useKvs;
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
	    if ($this->usesKvs()) {
	        $response = $this->getKvsCallResponse($uri, $params);

	        if (\is_array($response)) {
	            return $response;
            }
        }

		$response = \apiCall('dislo', $uri, \json_encode($params));

		$decodedBody = \json_decode($response->body, true);
		if (\json_last_error() == JSON_ERROR_NONE) {
			return $decodedBody;
		}

		throw new InvalidResponseData($response->body);
	}

    /**
     * @param string $uri
     * @param array  $parameter
     *
     * @return array|null
     */
	private function getKvsCallResponse($uri, array $parameter) {
	    //decide method call using
	    try {
	        switch ($uri) {
                case self::API_URI_PACKAGE_LIST:
                    $response = $this->getPackageListFromKvs();

                    break;
                case self::API_URI_MISC_REDIRECTOR_CONFIGURATION:
                    $response = $this->getMiscRedirectorConfigurationFromKvs();

                    break;
                default:
                    $response = null;
            }
        } catch (KVSKeyNotFoundException $e) {
	        return null;
        }

        return $response;
    }

    /**
     * @return array
     */
    private function getPackageListFromKvs() {
        return [
            'packages' => $this->getKvsApi()->get('apiPackages')
        ];
    }

    /**
     * @return array
     */
    private function getMiscRedirectorConfigurationFromKvs() {
        return \json_decode(\json_encode($this->getKvsApi()->get('redirectorData')), true);
    }

}