<?php

namespace Ixolit\Dislo\CDE\Interfaces;

use Ixolit\Dislo\CDE\Exceptions\InvalidStatusCodeException;
use Psr\Http\Message\ResponseInterface;

interface ResponseAPI {
	/**
	 * Sends out a PSR-7 response.
	 *
	 * @param ResponseInterface $response
	 */
	public function sendPSR7(ResponseInterface $response);

	/**
	 * Performs a permanent or temporary redirect to the location specified via $location. Performs a permanent or
	 * temporary redirect to the location specified via $location.
	 *
	 * This function is only supported within pages. Templates residing within the files/ or static/ folders should
	 * not call this function.
	 *
	 * @param string $location
	 * @param bool   $permanent specifies if the redirect should be permanent (Status Code 301 - "Moved Permanently"),
	 *                          or temporary (Status Code 302 - "Found").
	 */
	public function redirectTo($location, $permanent = false);

	/**
	 * Performs a permanent or temporary redirect to the page path specified via $page.
	 *
	 * This function is only supported within pages. Templates residing within the files/ or static/ folders should
	 * not call this function.
	 *
	 * @param string      $page      is a relative path to a local page on the site (e.g. "/about/legal").
	 * @param string|null $lang
	 * @param bool        $permanent specifies if the redirect should be permanent (Status Code 301 - "Moved
	 *                               Permanently"), or temporary (Status Code 302 - "Found").
	 */
	public function redirectToPage($page, $lang = null, $permanent = false);

	/**
	 * Sets the content type for the current response. Useful for sending out JSON.
	 *
	 * @param string $contentType
	 */
	public function setContentType($contentType);

	/**
	 * Set the HTTP response's status code.
	 *
	 * @param int $statusCode
	 *
	 * @throws InvalidStatusCodeException if the status code is invalid.
	 */
	public function setStatusCode($statusCode);
}
