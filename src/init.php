<?php

namespace Ixolit\Dislo\CDE;

$controllerLogic = new ControllerLogic(
	CDE::getRequestAPI(),
	CDE::getResponseAPI(),
	CDE::getFilesystemAPI()
);

$controllerLogic->execute();