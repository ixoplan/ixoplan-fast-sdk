<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

class ViewModel {
	private $data = [];

	public function __construct(array $data) {
		$this->data = $data;
	}

	public function html($variable) {
		return \html($this->data[$variable]);
	}

	public function xml($variable) {
		return \xml($this->data[$variable]);
	}

	public function js($variable) {
		return \js($this->data[$variable]);
	}
}