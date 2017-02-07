<?php

namespace Ixolit\Dislo\CDE;

class CDEInit extends \Ixolit\CDE\CDEInit {
	public static function execute() {
		global $cdeToolbar;

		$cdeToolbar = new \Ixolit\Dislo\CDE\CDEToolbar(
			CDE::getRequestAPI(),
			CDE::getResponseAPI(),
			CDE::getFilesystemAPI(),
			[
				'/tests'
			]
		);

		parent::execute();
	}
}
