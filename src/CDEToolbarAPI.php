<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\CDE\Interfaces\FilesystemAPI;
use Ixolit\CDE\Interfaces\RequestAPI;
use Ixolit\CDE\Interfaces\ResponseAPI;

final class CDEToolbarAPI {

	/** @var CDEToolbar */
	private static $toolbar;

	public static function init(RequestAPI $requestAPI, ResponseAPI $responseAPI, FilesystemAPI $filesystemAPI) {
		self::$toolbar = new CDEToolbar(
			$requestAPI,
			$responseAPI,
			$filesystemAPI,
			[
				(defined('APP_DIR') ? APP_DIR : '/') . 'tests'
			]
		);

	}

	public static function renderPreviewInfo() {

		if (self::$toolbar instanceof CDEToolbar) {
			self::$toolbar->renderToolbar();
		}
	}
}