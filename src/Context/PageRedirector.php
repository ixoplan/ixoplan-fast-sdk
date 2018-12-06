<?php

namespace Ixolit\Dislo\CDE\Context;


use Ixolit\CDE\Context\Page;
use Ixolit\Dislo\Client;

class PageRedirector {

	public static function redirect(Page $page, Client $disloClient) {

		try {
			$config = $disloClient->miscGetRedirectorConfiguration();
			if ($config && $config->getRedirector()) {
				$redirector = $config->getRedirector();
				if ($redirector) {
					$redirector->evaluate(new PageRedirectorRequest($page), new PageRedirectorResult($page));
				}
			}
		}
		catch (\Exception $e) {
			// ignore errors
		}
	}
}