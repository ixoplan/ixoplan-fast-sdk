<?php

namespace Ixolit\Dislo\CDE;

/**
 * @deprecated use CDEToolbarAPI
 */
class CDEInit extends \Ixolit\CDE\CDEInit {

	public static function execute() {

		// TODO: get rid of this really ugly global!
		global $cdeToolbar;

        $appDir = defined('APP_DIR') ? APP_DIR : '/';

		$cdeToolbar = new CDEToolbar(
			CDE::getRequestAPI(),
			CDE::getResponseAPI(),
			CDE::getFilesystemAPI(),
			[
				$appDir . 'tests'
			]
		);

		parent::execute();
	}

	public static function renderPreviewInfo() {

		global $cdeToolbar;

		if ($cdeToolbar instanceof CDEToolbar) {
			$cdeToolbar->renderToolbar();
		}
	}
}
