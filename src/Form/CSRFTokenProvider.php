<?php

namespace Ixolit\Dislo\CDE\Form;

interface CSRFTokenProvider {
	/**
	 * @return string
	 */
	public function getCSRFToken();
}
