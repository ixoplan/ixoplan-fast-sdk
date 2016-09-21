<?php

namespace Ixolit\Dislo\CDE\Exceptions;

/**
 * States that a variable with an invalid type was passed.
 *
 * This class was ported from the Opsbears Foundation library with authorization.
 */
class InvalidTypeException extends \Exception implements CDEException  {
	/**
	 * @param mixed  $object   the object which does not match the required type
	 * @param string $required the type required
	 */
	public function __construct($object, $required = "") {
		$type = \gettype($object);
		if ($type == 'object') {
			$type = \get_class($object);
		}
		$message = 'Invalid object type: ' . $type;
		if ($required) {
			$message .= ' expected ' . $required;
		}

		parent::__construct($message);
	}
}