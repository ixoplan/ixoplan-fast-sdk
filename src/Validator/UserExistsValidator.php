<?php

// TODO: dislo

namespace Ixolit\Dislo\CDE\Validator;

use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;

class UserExistsValidator implements FormValidator {
	/**
	 * {@inheritdoc}
	 */
	public function getKey() {
		return 'user-not-found';
	}

	/**
	 * {@inheritdoc}
	 */
	public function isValid($value) {
		$client = new CDEDisloClient();
		if (!$value) {
			return false;
		}
		try {
			$client->userFind($value)->getUser();
			return true;
		} catch (ObjectNotFoundException $e) {
			return false;
		}
	}
}