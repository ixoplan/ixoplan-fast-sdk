<?php

namespace Ixolit\Dislo\CDE\Interfaces;

use Ixolit\Dislo\CDE\Form\Form;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface FormProcessorInterface {
	/**
	 * Store the form, and modify the response to include any data that is needed.
	 *
	 * @param Form              $form
	 * @param ResponseInterface $response
	 *
	 * @return ResponseInterface
	 */
	public function store(Form $form, ResponseInterface $response);

	/**
	 * Restores the form from storage and returns if the restore process was successful.
	 *
	 * @param Form                   $form
	 * @param ServerRequestInterface $request
	 *
	 * @return bool
	 */
	public function restore(Form $form, ServerRequestInterface $request);

	/**
	 * Cleanup the form from storage.
	 *
	 * @param Form              $form
	 * @param ResponseInterface $response
	 *
	 * @return ResponseInterface
	 */
	public function cleanup(Form $form, ResponseInterface $response);
}
