<?php

namespace Ixolit\Dislo\CDE\Form;

use Ixolit\Dislo\CDE\Interfaces\FormProcessorInterface;
use Ixolit\Dislo\CDE\Interfaces\RequestAPI;
use Ixolit\Dislo\CDE\Interfaces\ResponseAPI;
use Ixolit\Dislo\CDE\PSR7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

abstract class ControllerFormHandler {
	//region Abstract functions
	/**
	 * @var ResponseAPI
	 */
	private $responseAPI;
	/**
	 * @var CSRFTokenProvider
	 */
	private $csrfTokenProvider;

	/**
	 * This call should create the form object.
	 *
	 * @return Form
	 */
	abstract protected function createForm();

	/**
	 * Function to call if the form has no errors. Should return a redirect to the page after the form has been
	 * processed.
	 *
	 * @see self::storeAndCeateRedirectResponse
	 * @see self::cleanAndCeateRedirectResponse
	 *
	 * @param ServerRequestInterface $request
	 * @param Form $form
	 *
	 * @return ResponseInterface|array
	 */
	abstract protected function onSuccess(ServerRequestInterface $request, Form $form);
	//endregion

	//region setter-getter
	/**
	 * @var RequestAPI
	 */
	private $requestAPI;

	/**
	 * @var FormProcessorInterface
	 */
	private $formProcessor;

	public function __construct(
		RequestAPI $requestAPI,
		ResponseAPI $responseAPI,
		FormProcessorInterface $formProcessor,
		CSRFTokenProvider $csrfTokenProvider
	) {
		$this->requestAPI = $requestAPI;
		$this->formProcessor = $formProcessor;
		$this->responseAPI = $responseAPI;
		$this->csrfTokenProvider = $csrfTokenProvider;
	}

	/**
	 * @return FormProcessorInterface
	 */
	protected function getFormProcessor() {
		return $this->formProcessor;
	}

	/**
	 * @return RequestAPI
	 */
	protected function getRequestAPI() {
		return $this->requestAPI;
	}

	/**
	 * @return ResponseAPI
	 */
	protected function getResponseAPI() {
		return $this->responseAPI;
	}
	//endregion

	public function isFormPost() {
		$parameters = $this->requestAPI->getRequestParameters();
		if (!isset($parameters['_form'])) {
			return false;
		}
		if ($parameters['_form'] == $this->getForm()->getKey()) {
			return true;
		}
		return false;
	}

	public function getForm() {
		$form = $this->createForm();

		$request = $this->requestAPI->getPSR7();

		return $this->onRender($request, $form);
	}

	public function handleFormPost() {
		$form = $this->createForm();

		$request = $this->requestAPI->getPSR7();
		if (\count($this->requestAPI->getRequestParameters())) {
			if (\count($errors = $form->setFromRequest($request)) > 0) {
				return $this->onError($request, $form);
			} else {
				return $this->onSuccess($request, $form);
			}
		} else {
			return [
				'form' => $this->onRender($request, $form)
			];
		}
	}

	//region Helper functions
	protected function storeAndCreateRedirectResponse(Form $form, UriInterface $uri) {
		$response = new Response(
			302,
			['Location' => [(string)$uri]],
			'',
			'1.1'
		);
		return $this->getFormProcessor()->store($form, $response);
	}

	protected function cleanAndCreateRedirectResponse(Form $form, UriInterface $uri) {
		$response = new Response(
			302,
			['Location' => [(string)$uri]],
			'',
			'1.1'
		);
		return $this->getFormProcessor()->cleanup($form, $response);
	}

	/**
	 * @return CSRFTokenProvider
	 */
	protected function getCsrfTokenProvider() {
		return $this->csrfTokenProvider;
	}
	//endregion

	//region State handlers
	/**
	 * Function to call if the form has an error. Should return a redirect to the current after the form has been
	 * processed.
	 *
	 * @see self::storeAndCeateRedirectResponse
	 * @see self::cleanAndCeateRedirectResponse
	 *
	 * @param ServerRequestInterface $request
	 * @param Form $form
	 *
	 * @return ResponseInterface|array
	 */
	protected function onError(ServerRequestInterface $request, Form $form) {
		return $this->storeAndCreateRedirectResponse($form, $request->getUri()->withQuery(''));
	}

	/**
	 * Function to call when the form is being rendered. Returns a view-compatible array with the form in the 'form'
	 * key.
	 *
	 * @param ServerRequestInterface $request
	 * @param Form                   $form
	 *
	 * @return Form
	 */
	protected function onRender(ServerRequestInterface $request, Form $form) {
		$this->formProcessor->restore($form, $request);
		return $form;
	}
	//endregion
}