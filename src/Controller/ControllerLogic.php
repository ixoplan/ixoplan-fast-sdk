<?php

namespace Ixolit\Dislo\CDE\Controller;

use Ixolit\Dislo\CDE\Auth\AuthenticationRequiredException;
use Ixolit\Dislo\CDE\Exceptions\ControllerSkipViewException;
use Ixolit\Dislo\CDE\Interfaces\FilesystemAPI;
use Ixolit\Dislo\CDE\Interfaces\RequestAPI;
use Ixolit\Dislo\CDE\Interfaces\ResponseAPI;
use Ixolit\Dislo\CDE\PSR7\Response;
use Ixolit\Dislo\CDE\WorkingObjects\ViewModel;
use Psr\Http\Message\ResponseInterface;

class ControllerLogic {
	/**
	 * @var RequestAPI
	 */
	private $requestApi;

	/**
	 * @var ResponseAPI
	 */
	private $responseApi;

	/**
	 * @var FilesystemAPI
	 */
	private $fsApi;

	public function __construct(RequestAPI $requestApi, ResponseAPI $responseApi, FilesystemAPI $fsApi) {
		$this->requestApi  = $requestApi;
		$this->responseApi = $responseApi;
		$this->fsApi = $fsApi;
	}

	public function execute() {
		global $view;

		try {
			$path = '/vhosts/';
			$path .= urlencode($this->requestApi->getEffectiveVhost());
			$path .= '/layouts/';
			$path .= $this->requestApi->getLayout()->getName();
			$path .= '/pages';
			$path .= rtrim($this->requestApi->getPagePath(), '/');
			$path .= '/controller.php';

			$viewData = [];
			if ($this->fsApi->exists($path)) {
				try {
					$controllerData = include($path);
				} catch (ControllerSkipViewException $e) {
					exit;
				}
				if ($controllerData instanceof ResponseInterface) {
					$this->responseApi->sendPSR7($controllerData);
					exit;
				}
				foreach ($controllerData as $key => $value) {
					$viewData[$key] = $value;
				}
			}
			$view = new ViewModel($viewData);
		} catch (AuthenticationRequiredException $e) {
			if ($loginPage = getMeta('loginPage')) {
				$currentUri = $this->requestApi->getPSR7()->getUri();
				$newUri = $currentUri
					->withPath('/' . $this->requestApi->getLanguage() . $loginPage)
					->withQuery('backurl=' . \urlencode($currentUri->getPath()));
				$this->responseApi->sendPSR7(new Response(302, [
					'Location' => [(string)$newUri]
				], '', '1.1'));
			} else {
				throw $e;
			}
		} catch (\Exception $e) {
			if (\function_exists('previewInfo') && previewInfo()) {
				include(__DIR__ . '/errorpage.php');
				exit;
			} else {
				throw $e;
			}
		}
	}
}