<?php

namespace Ixolit\Dislo\CDE\Validator;

use Ixolit\CDE\Validator\FormValidator;
use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\Client;
use Ixolit\Dislo\Exceptions\ObjectNotFoundException;

class UserExistsValidator implements FormValidator {

    /**
     * @var Client|CDEDisloClient
     */
    private $disloClient;

    /**
     * UserExistsValidator constructor.
     *
     * @param Client|null $disloClient
     */
    public function __construct(Client $disloClient = null) {
        $this->disloClient = $disloClient ?: new CDEDisloClient();
    }

    /**
     * @return mixed
     */
    protected function getDisloClient() {
        return $this->disloClient;
    }

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
		try {
			$this->getDisloClient()->userFind($value)->getUser();
			return true;
		} catch (ObjectNotFoundException $e) {
			return false;
		}
	}
}