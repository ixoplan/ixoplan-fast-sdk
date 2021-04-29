<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\CDE\Exceptions\KVSKeyNotFoundException;
use Ixolit\Dislo\CDE\Request\CDERequestClient;
use Ixolit\Dislo\Client;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;
use Ixolit\Dislo\Request\RequestClient;
use Ixolit\Dislo\Response\MiscGetRedirectorConfigurationResponse;
use Ixolit\Dislo\Response\PackageGetResponse;
use Ixolit\Dislo\Response\PackagesListResponse;
use Ixolit\Dislo\WorkingObjects\Package;

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
		$packages = $this->packagesList(null, $cached, [$packageIdentifier])->getPackages();
		foreach ($packages as $package) {
			if ($package->getPackageIdentifier() == $packageIdentifier) {
				return new PackageGetResponse($package);
			}
		}
		throw new ObjectNotFoundException('package with ID ' . $packageIdentifier);
	}

    /**
     * Retrieve a list of all packages registered in the system. This version uses the CDE key-value store to make
     * package list retrieval faster.
     *
     * @param null|string $serviceIdentifier
     * @param bool        $cached Use key-value store cached packages list instead of fetching it from the API.
     * @param array       $packageIdentifiers
     * @param bool        $onlyAvailable
     *
     * @return \Ixolit\Dislo\Response\PackagesListResponse
     */
	public function packagesList(
		$serviceIdentifier = null,
		$cached = false,
        array $packageIdentifiers = [],
        $onlyAvailable = false
	) {
		if (!$cached || $this->getDevMode()) {
			return parent::packagesList($serviceIdentifier, $packageIdentifiers, $onlyAvailable);
		} else {
			try {
			    return new PackagesListResponse($this->getPackagesFromKVS($serviceIdentifier, $packageIdentifiers, $onlyAvailable));
			} catch (KVSKeyNotFoundException $e) {
				return parent::packagesList($serviceIdentifier, $packageIdentifiers, $onlyAvailable);
			}
		}
	}

    /**
     * @param int|null $serviceIdentifier
     * @param string[] $packageIdentifiers
     * @param bool     $onlyAvailable
     *
     * @return Package[]
     *
     * @throws KVSKeyNotFoundException
     */
	private function getPackagesFromKVS($serviceIdentifier = null, array $packageIdentifiers = [], $onlyAvailable = false)
    {
        $packages = PackagesListResponse::fromResponse(
            [
                'packages' => CDE::getKVSAPI()->get('apiPackages')
            ]
        )->getPackages();

        if ($serviceIdentifier) {
            $packages = $this->filterPackagesByServiceIdentifier($packages, $serviceIdentifier);
        }
        if (!empty($packageIdentifiers)) {
            $packages = $this->filterPackagesByIdentifiers($packages, $packageIdentifiers);
        }
        if ($onlyAvailable) {
            $packages = $this->filterAvailablePlans($packages);
        }

        return $packages;
    }

    /**
     * @param array $packages
     * @param int   $serviceIdentifier
     */
    private function filterPackagesByServiceIdentifier(array $packages, $serviceIdentifier)
    {
        return \array_filter(
            $packages,
            function (Package $package) use ($serviceIdentifier) {
                return $package->getServiceIdentifier() == $serviceIdentifier;
            }
        );
    }

    /**
     * @param Package[] $packages
     * @param string[]  $packageIdentifiers
     *
     * @return Package[]
     */
	private function filterPackagesByIdentifiers(array $packages, array $packageIdentifiers)
    {
        return \array_filter(
            $packages,
            function (Package $package) use ($packageIdentifiers) {
                return \in_array($package->getPackageIdentifier(), $packageIdentifiers);
            }
        );
    }

    /**
     * @param Package[] $packages
     *
     * @return Package[]
     */
	private function filterAvailablePlans(array $packages)
    {
        return \array_filter(
            $packages,
            function (Package $package) {
                return $package->isSignupAvailable();
            }
        );
    }

	/**
	 * Retrieve Ixoplan's redirector configuration. This version uses the FAST key-value store to make retrieval faster.
	 *
	 * @param bool $cached
	 * @return \Ixolit\Dislo\Response\MiscGetRedirectorConfigurationResponse
	 */
	public function miscGetRedirectorConfiguration($cached = true) {

		if (!$cached || $this->getDevMode()) {
			return parent::miscGetRedirectorConfiguration();
		}
		else {
			try {
				return MiscGetRedirectorConfigurationResponse::fromData(
					CDE::getKVSAPI()->get('redirectorData')
				);
			}
			catch (KVSKeyNotFoundException $e) {
				return parent::miscGetRedirectorConfiguration();
			}
		}

	}
}
