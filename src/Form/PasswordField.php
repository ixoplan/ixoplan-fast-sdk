<?php

namespace Ixolit\Dislo\CDE\Form;

class PasswordField extends TextField {
	public function __construct($name) {
		parent::__construct($name);
		$this->setMasked(true);
	}

	public function getType() {
		return 'password';
	}
}
