<?php

namespace Ixolit\Dislo\CDE\Validator;

class CSRFTokenValidator extends ExactValueValidator {
	public function getKey() {
		return 'csrf';
	}
}
