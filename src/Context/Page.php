<?php

namespace Ixolit\Dislo\CDE\Context;

use Ixolit\CDE\Context\Page as CDEPage;
use Ixolit\Dislo\CDE\CDE;
use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\CDE\CDEInit;

class Page extends CDEPage {

	/** @var CDEDisloClient */
	private $cdeDislolient;

	/**
	 * @return CDEDisloClient
	 */
	protected function newCdeDisloClient() {
		return CDE::getDisloClient();
	}

	/**
	 * @return CDEDisloClient
	 */
	public function getCdeDisloClient() {
		if (!isset($this->cdeDislolient)) {
			$this->cdeDislolient = $this->newCdeDisloClient();
		}

		return $this->cdeDislolient;
	}

	protected function doRedirects() {

		$redirector = null;
		try {
			$config = $this->getCdeDisloClient()->miscGetRedirectorConfiguration();
			if ($config && $config->getRedirector()) {
				$redirector = $config->getRedirector();
			}
		}
		catch (\Exception $e) {
			// ignore errors
		}

		if ($redirector) {
			$result = $redirector->evaluate(new PageRedirectorRequest($this));
			if ($result) {
				// TODO: separate method?

				foreach ($result->getCookies() as $cookie) {
					$this->getResponseAPI()->setCookie(
						$cookie->getName(),
						$cookie->getValue(),
						$cookie->getExpirationDateTime()
							? $cookie->getExpirationDateTime()->getTimestamp() - time()
							: null,
						$cookie->getPath(),
						null,
						$cookie->isRequireSSL(),
						$cookie->isHttpOnly()
					);
				}

				if ($result->isRedirect()) {
					setStatusCode($result->getStatusCode());
					setHeader('Location', $result->getUrl());
					exit;
				}
			}
		}
	}

	protected function doPrepare() {
		parent::doPrepare();
		$this->doRedirects();
	}

	protected function doExecute() {
		// call Dislo/CDE controller logic
		CDEInit::execute();
	}
}