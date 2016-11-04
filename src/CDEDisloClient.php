<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\KVSKeyNotFoundException;
use Ixolit\Dislo\Client;

class CDEDisloClient extends Client {
	/**
	 *
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
			//todo add KVS shortcut support once Dislo supports storing packages in the new format.
			/*try {
				$kvsPackageData = CDE::getKVSAPI()->get('packages');
				var_dump($kvsPackageData);
				exit;
			} catch (KVSKeyNotFoundException $e) {*/
				return parent::packagesList($serviceIdentifier);
			//}
		}
	}
}
