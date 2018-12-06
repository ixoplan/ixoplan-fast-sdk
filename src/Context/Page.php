<?php

namespace Ixolit\Dislo\CDE\Context;

use Ixolit\CDE\Context\Page as CDEPage;
use Ixolit\Dislo\CDE\CDE;
use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\CDE\CDEInit;
use Ixolit\Dislo\Redirector\Base\RedirectorRequestInterface;
use Ixolit\Dislo\Redirector\Base\RedirectorResultInterface;

/**
 * Class Page
 *
 * @package Ixolit\Dislo\CDE\Context
 *
 * @deprecated use PageRedirector and similar helpers
 */
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

	/**
	 * @return RedirectorRequestInterface
	 */
	protected function getRedirectorRequest() {
		return new PageRedirectorRequest($this);
	}

	/**
	 * @return RedirectorResultInterface
	 */
	protected function getRedirectorResult() {
		return new PageRedirectorResult($this);
	}

	protected function doRedirects() {

		try {
			$config = $this->getCdeDisloClient()->miscGetRedirectorConfiguration();
			if ($config && $config->getRedirector()) {
				$redirector = $config->getRedirector();
				if ($redirector) {
					$redirector->evaluate($this->getRedirectorRequest(), $this->getRedirectorResult());
				}
			}
		}
		catch (\Exception $e) {
			// ignore errors
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