<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Request\CDERequestClient;
use Ixolit\Dislo\Client;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;
use Ixolit\Dislo\Request\RequestClient;
use Ixolit\Dislo\Response\PackageGetResponse;

/**
 * Class CDEDisloClient
 *
 * @package Ixolit\Dislo\CDE
 *
 * @deprecated use default Ixoplan client with CDERequestClient
 */
class CDEDisloClient extends Client {

	/** @var CDERequestClient */
	private $cdeRequestClient;

	/**
	 * Initialize the client. If no RequestClient is passed, this class attempts to use the FAST-internal API method.
	 *
	 * @param RequestClient|null $requestClient  Default to internal API method when running in the FAST.
	 * @param bool               $forceTokenMode Force using tokens. Does not allow passing a user Id.
	 */
	public function __construct(RequestClient $requestClient = null, $forceTokenMode = true) {
		if (!isset($requestClient) && \function_exists('\\apiCall')) {
			$requestClient = new CDERequestClient();
		}
		if ($requestClient instanceof CDERequestClient) {
			$this->cdeRequestClient = $requestClient;
		}
		parent::__construct($requestClient, $forceTokenMode);
	}

	/**
	 * executing callback with temporarily switched cache mode
	 *
	 * @param $call
	 * @param $cached
	 * @return mixed
	 */
	private function exec($call, $cached) {
		if ($this->cdeRequestClient instanceof CDERequestClient) {
			$useKvs = $this->cdeRequestClient->getUseKvs();
			$this->cdeRequestClient->setUseKvs($cached);
			$result = $call();
			$this->cdeRequestClient->setUseKvs($useKvs);
		}
		else {
			$result = $call();
		}
		return $result;
	}

	/**
	 * @param string $packageIdentifier
	 *
	 * @param bool   $cached
	 *
	 * @return PackageGetResponse
	 * @throws ObjectNotFoundException
	 */
	public function packageGet($packageIdentifier, $cached = true) {
		return $this->exec(
			function () use ($packageIdentifier) {
				return parent::packageGet($packageIdentifier);
			},
			$cached
		);
	}

    /**
     * Retrieve a list of all packages registered in the system. This version uses the CDE key-value store to make
     * package list retrieval faster.
     *
     * @param null|string $serviceIdentifier
     * @param bool        $cached Use key-value store cached packages list instead of fetching it from the API.
     * @param string[]    $packageIdentifiers
     * @param bool        $onlyAvailable
     *
     * @return \Ixolit\Dislo\Response\PackagesListResponse
     */
	public function packagesList($serviceIdentifier = null, $cached = false, array $packageIdentifiers = [], $onlyAvailable = false) {
		return $this->exec(
			function () use ($serviceIdentifier, $packageIdentifiers, $onlyAvailable) {
				return parent::packagesList($serviceIdentifier, $packageIdentifiers, $onlyAvailable);
			},
			$cached
		);
	}

	/**
	 * Retrieve Ixoplan's redirector configuration. This version uses the FAST key-value store to make retrieval faster.
	 *
	 * @param bool $cached
	 * @return \Ixolit\Dislo\Response\MiscGetRedirectorConfigurationResponse
	 */
	public function miscGetRedirectorConfiguration($cached = true) {
		return $this->exec(
			function () {
				return parent::miscGetRedirectorConfiguration();
			},
			$cached
		);
	}
}
