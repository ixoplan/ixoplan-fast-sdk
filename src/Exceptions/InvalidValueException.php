<?php

namespace Ixolit\Dislo\CDE\Exceptions;

/**
 * This Exception states, that an invalid value was provided.
 *
 * This class was ported from the Opsbears Foundation library with authorization.
 */
class InvalidValueException extends \Exception implements CDEException {
	private static function isStringConvertible($string) {
		if (\is_int($string) || \is_bool($string) || \is_float($string) || \is_string($string)) {
			return true;
		}
		if (\is_object($string) && \method_exists($string, '__toString')) {
			return true;
		}
		return false;
	}

	/**
	 * @param mixed  $value    the value which does not match the required type
	 * @param string $required the type required
	 */
	public function __construct($value, $required = "") {
		if (\is_object($value)) {

			if (\method_exists($value, '__toString')) {
				$string = (string)$value;
				$value  = \get_class($value) . '(' . \var_export($string, true) . ')';
			} else {
				$value  = \get_class($value);
			}
		} else if (!self::isStringConvertible($value)) {
			$value = \var_export($value, true);
		}
		$message = 'Invalid value: ' . $value;
		if ($required) {
			$message .= ' expected ' . $required;
		}

		parent::__construct($message);
	}
}
