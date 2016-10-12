<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\ControllerSkipViewException;
use Ixolit\Dislo\CDE\Interfaces\FilesystemAPI;
use Ixolit\Dislo\CDE\Interfaces\RequestAPI;
use Ixolit\Dislo\CDE\Interfaces\ResponseAPI;

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
		$path = '/vhosts/';
		$path .= urlencode($this->requestApi->getVhost());
		$path .= '/layouts/';
		$path .= \urlencode($this->requestApi->getLayout());
		$path .= '/pages/';
		$path .= $this->requestApi->getPagePath();
		$path .= '/controller.php';

		var_dump($path);
		exit;

		if ($this->fsApi->exists($path)) {
			try {
				$controllerData = include($path);
			} catch (ControllerSkipViewException $e) {
				exit;
			}
			$GLOBALS = $controllerData;
		}
	}
}