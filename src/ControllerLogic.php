<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\ControllerSkipViewException;
use Ixolit\Dislo\CDE\Interfaces\FilesystemAPI;
use Ixolit\Dislo\CDE\Interfaces\RequestAPI;
use Ixolit\Dislo\CDE\Interfaces\ResponseAPI;
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


		$path = '/vhosts/';
		$path .= urlencode($this->requestApi->getVhost());
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
			} catch (\Exception $e) {
				if (\function_exists('previewInfo') && previewInfo()) {
					var_dump($e);
					exit;
				} else {
					//todo add proper error page
					throw $e;
				}
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
	}
}