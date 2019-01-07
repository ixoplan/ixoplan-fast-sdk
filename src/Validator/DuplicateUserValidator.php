<?php

namespace Ixolit\Dislo\CDE\Validator;

use Ixolit\CDE\Validator\FormValidator;
use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\Client;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;

class DuplicateUserValidator implements FormValidator {
	/**
	 * @var int|null
	 */
	private $exceptUserId;

    /**
     * @var Client|CDEDisloClient
     */
	private $disloClient;

    /**
     * @param int|null    $exceptUserId allow this user to have the same e-mail address.
     * @param Client|null $disloClient
     */
	public function __construct($exceptUserId = null, Client $disloClient = null) {
		$this->exceptUserId = $exceptUserId;
		$this->disloClient = $disloClient ?: new CDEDisloClient();
	}

    /**
     * @return CDEDisloClient
     */
    protected function getDisloClient() {
        return $this->disloClient;
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
		if (!$value) {
			return true;
		}
		try {
			$userId = $this->disloClient->userFind($value)->getUser()->getUserId();
			if ($this->exceptUserId !== null && $userId == $this->exceptUserId) {
				return true;
			}
			return false;
		} catch (ObjectNotFoundException $e) {
			return true;
		}
	}
}