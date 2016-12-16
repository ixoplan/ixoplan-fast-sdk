<?php

namespace Ixolit\Dislo\CDE\Validator;

use Ixolit\Dislo\CDE\Validator\FormValidator;
use Ixolit\Dislo\Client;
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
		$client = new Client();
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