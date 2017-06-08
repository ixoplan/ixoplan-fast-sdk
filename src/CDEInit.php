<?php

namespace Ixolit\Dislo\CDE;

class CDEInit extends \Ixolit\CDE\CDEInit {

	public static function execute() {

		// TODO: get rid of this really ugly global!
		global $cdeToolbar;

		$cdeToolbar = new CDEToolbar(
			CDE::getRequestAPI(),
			CDE::getResponseAPI(),
			CDE::getFilesystemAPI(),
			[
				'/tests'
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
