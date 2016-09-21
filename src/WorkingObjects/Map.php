<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

use Ixolit\Dislo\CDE\Exceptions\InvalidTypeException;

/**
 * This is a generic Map (key-value) class, which can be used as an object
 * container and overridden by child classes. Can be accessed as a native PHP
 * array.
 *
 * This class was ported from the Opsbears Foundation library with authorization.
 */
class Map extends ArrayObject {
	/**
	 * Throws a InvalidTypeException, if $offset is not a string
	 *
	 * @param string $offset
	 *
	 * @throws InvalidTypeException if $offset is not a string.
	 */
	final protected function keyCheck($offset) {
		if (\is_int($offset) || \is_bool($offset) || \is_float($offset) || \is_string($offset)) {
			return;
		}
		if (\is_object($offset) && \method_exists($offset, '__toString')) {
			return;
		}
		throw new InvalidTypeException($offset, 'string-convertible');
	}
}
