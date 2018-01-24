<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Context\Page;
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
 * @deprecated this client is not in use anymore
 */
class CDEDisloClient extends Client {

	/**
	 * Initialize the client. If no RequestClient is passed, this class attempts to use the CDE-internal API method.
	 *
	 * @param RequestClient|null $requestClient  Default to internal API method when running in the CDE.
	 * @param bool               $forceTokenMode Force using tokens. Does not allow passing a user Id.
	 */
	public function __construct(RequestClient $requestClient = null, $forceTokenMode = true) {
		if (!isset($requestClient) && \function_exists('\\apiCall')) {
			$requestClient = new CDERequestClient(Page::kvsAPI(), false);
		}
		parent::__construct($requestClient, $forceTokenMode);
	}

    /**
     * @param bool $cached
     *
     * @return CDERequestClient
     */
	private function createRequestClient($cached) {
	    return new CDERequestClient(Page::kvsAPI(), $cached);
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
	    $this->setRequestClient($this->createRequestClient($cached));

	    return parent::packageGet($packageIdentifier);
	}

	/**
	 * Retrieve a list of all packages registered in the system. This version uses the CDE key-value store to make
	 * package list retrieval faster.
	 *
	 * @param null|string $serviceIdentifier
	 * @param bool        $cached Use key-value store cached packages list instead of fetching it from the API.
	 *
	 * @return \Ixolit\Dislo\Response\PackagesListResponse
	 */
	public function packagesList($serviceIdentifier = null, $cached = false) {
        $this->setRequestClient($this->createRequestClient($cached));

        return parent::packagesList($serviceIdentifier);
	}

	/**
	 * Retrieve Dislo's redirector configuration. This version uses the CDE key-value store to make retrieval faster.
	 *
	 * @param bool $cached
	 * @return \Ixolit\Dislo\Response\MiscGetRedirectorConfigurationResponse
	 */
	public function miscGetRedirectorConfiguration($cached = true) {
	    $this->setRequestClient($this->createRequestClient($cached));

	    return parent::miscGetRedirectorConfiguration();
	}
}
