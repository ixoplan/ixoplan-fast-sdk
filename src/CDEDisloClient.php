<?php

// TODO: dislo

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\KVSKeyNotFoundException;
use Ixolit\Dislo\CDE\Request\CDERequestClient;
use Ixolit\Dislo\Client;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;
use Ixolit\Dislo\Request\RequestClient;
use Ixolit\Dislo\Response\PackageGetResponse;
use Ixolit\Dislo\Response\PackagesListResponse;

class CDEDisloClient extends Client {

	/**
	 * Initialize the client. If no RequestClient is passed, this class attempts to use the CDE-internal API method.
	 *
	 * @param RequestClient|null $requestClient  Default to internal API method when running in the CDE.
	 * @param bool               $forceTokenMode Force using tokens. Does not allow passing a user Id.
	 */
	public function __construct(RequestClient $requestClient = null, $forceTokenMode = true) {
		if (!isset($requestClient) && \function_exists('\\apiCall')) {
			$requestClient = new CDERequestClient();
		}
		parent::__construct($requestClient, $forceTokenMode);
	}

	/**
	 * @param string $packageIdentifier
	 *
	 * @param bool   $cached
	 *
	 * @return PackageGetResponse
	 * @throws ObjectNotFoundException
	 */
	public function packageGet(
		$packageIdentifier,
		$cached = true
	) {
		$packages = $this->packagesList(null, $cached)->getPackages();
		foreach ($packages as $package) {
			if ($package->getPackageIdentifier() == $packageIdentifier) {
				return new PackageGetResponse($package);
			}
		}
		throw new ObjectNotFoundException('package with ID ' . $packageIdentifier);
	}

	/**
	 * Retrieve a list of all packages registered in the system. This version uses the CDE key-value store to make
	 * package list ist retrieval faster.
	 *
	 * @param null|string $serviceIdentifier
	 * @param bool        $cached Use key-value store cached packages list instead of fetching it from the API.
	 *
	 * @return \Ixolit\Dislo\Response\PackagesListResponse
	 */
	public function packagesList(
		$serviceIdentifier = null,
		$cached = false
	) {
		if (!$cached) {
			return parent::packagesList($serviceIdentifier);
		} else {
			try {
				return PackagesListResponse::fromResponse(CDE::getKVSAPI()->get('apiPackages'));
			} catch (KVSKeyNotFoundException $e) {
				return parent::packagesList($serviceIdentifier);
			}
		}
	}
}
