<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Controller\ControllerLogic;

class CDEInit {
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

		$controllerLogic = new ControllerLogic(
			CDE::getRequestAPI(),
			CDE::getResponseAPI(),
			CDE::getFilesystemAPI()
		);

		$controllerLogic->execute();
	}
}
