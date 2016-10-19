<?php

namespace Ixolit\Dislo\CDE;

class CDEInit {
	public static function execute() {
		$controllerLogic = new ControllerLogic(
			CDE::getRequestAPI(),
			CDE::getResponseAPI(),
			CDE::getFilesystemAPI()
		);

		$controllerLogic->execute();
	}
}