<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\CDE\Interfaces\FilesystemAPI;
use Ixolit\CDE\Interfaces\RequestAPI;
use Ixolit\CDE\Interfaces\ResponseAPI;
use Ixolit\Dislo\CDE\UnitTest\CDETestRunner;

class CDEToolbar {
	private $previewInfo = null;

	/**
	 * @var RequestAPI
	 */
	private $request;
	/**
	 * @var ResponseAPI
	 */
	private $response;
	/**
	 * @var FilesystemAPI
	 */
	private $filesystemAPI;
	/**
	 * @var array
	 */
	private $unitTestDirectories;

	public function __construct(
		RequestAPI $request,
		ResponseAPI $response,
		FilesystemAPI $filesystemAPI,
		$unitTestDirectories = []
	) {

	    $appDir = defined('APP_DIR') ? APP_DIR  : '/';

		$unitTestDirectories[] = $appDir . 'vendor/ixolit/dislo-cde-sdk/tests/';
		$unitTestDirectories[] = $appDir . 'vendor/ixolit/dislo-sdk/tests/';

		if (\function_exists('previewInfo') && $previewInfo = previewInfo()) {
			$this->previewInfo = $previewInfo;
		}

		$this->request = $request;
		$this->response = $response;
		$this->filesystemAPI = $filesystemAPI;
		$this->unitTestDirectories = $unitTestDirectories;

		//Check if the request is directed at the toolbar and catch it
		$this->handleToolbarRequest();
	}

	private function handleToolbarRequest() {
		$requestParams = $this->request->getRequestParameters();
		if ($this->previewInfo && \array_key_exists('__cde_toolbar_action', $requestParams)) {
			switch ($requestParams['__cde_toolbar_action']) {
				case 'unittest_list':
					$this->response->setStatusCode(200);
					$this->response->setContentType('application/json');
					$testRunner = new CDETestRunner($this->filesystemAPI, $this->unitTestDirectories);
					echo(\json_encode($testRunner->getUnitTests()));
					exit;
				case 'unittest_execute':
					$this->response->setStatusCode(200);
					$this->response->setContentType('application/json');
					$testRunner = new CDETestRunner($this->filesystemAPI, $this->unitTestDirectories);
					echo(\json_encode($testRunner->execute(
						$requestParams['__cde_toolbar_unittest_class'],
						$requestParams['__cde_toolbar_unittest_method']
					)));
					exit;
			}
		}
	}

	public function renderToolbar() {
		if ($this->previewInfo) {
			$previewVersion = new \DateTime();
			$previewVersion->setTimestamp($this->previewInfo->timestamp / 1000);

			if (isset($this->previewInfo->leave_preview_url)) {
				$previewUrl = $this->previewInfo->leave_preview_url;
			} else {
				$previewUrl = '';
			}

			$testRunner = new CDETestRunner($this->filesystemAPI, $this->unitTestDirectories);
			$unitTests  = $testRunner->getUnitTests();

			require(preg_replace('/^vfs\:/', defined('APP_DIR') ? APP_DIR : '/', __DIR__ . '/../templates/toolbar/toolbar.php'));
		}
	}
}