<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\CDEFeatureNotSupportedException;
use Ixolit\Dislo\CDE\Exceptions\InvalidStatusCodeException;
use Ixolit\Dislo\CDE\Interfaces\ResponseAPI;
use Psr\Http\Message\ResponseInterface;

class CDEResponseAPI implements ResponseAPI {
	/**
	 * {@inheritdoc}
	 */
	public function redirectTo($location, $permanent = false) {
		if (!\function_exists('redirectTo')) {
			throw new CDEFeatureNotSupportedException('redirectTo');
		}
		redirectTo($location, $permanent);
	}

	/**
	 * {@inheritdoc}
	 */
	public function redirectToPage($page, $lang = null, $permanent = false) {
		if (!\function_exists('redirectToPage')) {
			throw new CDEFeatureNotSupportedException('redirectToPage');
		}
		redirectToPage($page, $lang, $permanent);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setContentType($contentType) {
		if (!\function_exists('setContentType')) {
			throw new CDEFeatureNotSupportedException('setContentType');
		}
		\setContentType($contentType);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setStatusCode($statusCode) {
		if (!\function_exists('setStatusCode')) {
			throw new CDEFeatureNotSupportedException('setStatusCode');
		}
		if (!\setStatusCode($statusCode)) {
			throw new InvalidStatusCodeException($statusCode);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function sendPSR7(ResponseInterface $response) {
		$this->setStatusCode($response->getStatusCode());
		$headers = $response->getHeaders();

		foreach ($headers as $header => $content) {
			switch ($header) {
				case 'content-type':
					$this->setContentType(\implode(';', $content));
					break;
				case 'location':
					$this->redirectTo(\implode(';',$content), ($response->getStatusCode()==301?true:false));
					break;
				default:
					throw new CDEFeatureNotSupportedException('Sending header type ' . $header . ' is not supported');
					break;
			}
		}

		echo $response->getBody();
	}
}
