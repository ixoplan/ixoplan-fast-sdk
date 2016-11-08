<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\KVSKeyNotFoundException;
use Ixolit\Dislo\Client;
use Ixolit\Dislo\Response\PackagesListResponse;
use Ixolit\Dislo\WorkingObjects\Package;

class CDEDisloClient extends Client {
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
