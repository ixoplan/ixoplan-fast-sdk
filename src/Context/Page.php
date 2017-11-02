<?php

namespace Ixolit\Dislo\CDE\Context;

use Ixolit\CDE\Context\Page as CDEPage;
use Ixolit\Dislo\CDE\CDEInit;

class Page extends CDEPage {

	protected function doExecute() {
		// call Dislo/CDE controller logic
		CDEInit::execute();
	}
}