<?php

namespace Ixolit\Dislo\CDE\Validator;

use Ixolit\CDE\Validator\FormValidator;
use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;

class UserEnabledValidator implements FormValidator {

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
		if (!$value) {
			return false;
		}
		$client = new CDEDisloClient();
		try {
			return !$client->userFind($value)->getUser()->isLoginDisabled();
		} catch (ObjectNotFoundException $e) {
			return false;
		}
	}
}