<?php

// TODO: dislo

namespace Ixolit\Dislo\CDE\Validator;

use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;

class DuplicateUserValidator implements FormValidator {
	/**
	 * @var int|null
	 */
	private $exceptUserId;

	/**
	 * @param int|null $exceptUserId allow this user to have the same e-mail address.
	 */
	public function __construct($exceptUserId = null) {
		$this->exceptUserId = $exceptUserId;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getKey() {
		return 'email-exists';
	}

	/**
	 * {@inheritdoc}
	 */
	public function isValid($value) {
		$client = new CDEDisloClient();
		if (!$value) {
			return true;
		}
		try {
			$userId = $client->userFind($value)->getUser()->getUserId();
			if ($this->exceptUserId !== null && $userId == $this->exceptUserId) {
				return true;
			}
			return false;
		} catch (ObjectNotFoundException $e) {
			return true;
		}
	}
}