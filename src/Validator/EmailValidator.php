<?php

namespace Ixolit\Dislo\CDE\Validator;

/**
 * Validates an e-mail address using both a formal method, as well as looking up the MX, A or AAAA record for the
 * domain.
 *
 * This class was ported from the Piccolo form library with permission.
 */
class EmailValidator implements FormValidator {
	const ERROR_INVALID_EMAIL = 'invalid-email';

	/**
	 * {@inheritdoc}
	 */
	public function getKey() {
		return self::ERROR_INVALID_EMAIL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isValid($value) {
		if (!$value) {
			return true;
		}

		if (!\preg_match('/@/', $value)) {
			return false;
		}

		list($local_part, $domain) = \explode('@', $value, 2);

		if (!$local_part || !$domain) {
			return false;
		}

		if (!\preg_match('/^[a-zA-Z0-9\-_\+\.\x{007F}-\x{FFFF}]+$/u', $local_part)) {
			return false;
		}

		//@todo DNS validation doesn't yet work
		/*if (!\dns_get_record($domain, DNS_MX)) {
			if (!\count(\dns_get_record($domain, DNS_A)) && !\count(\dns_get_record($domain, DNS_AAAA))) {
				return false;
			}
		}*/

		return true;
	}
}