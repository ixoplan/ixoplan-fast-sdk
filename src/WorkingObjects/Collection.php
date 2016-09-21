<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

use Ixolit\Dislo\CDE\Exceptions\InvalidTypeException;
use OutOfBoundsException;

/**
 * This is a generic Collection (array) class, which can be used as an object
 * container and overridden by child classes. Can be accessed like a native PHP
 * array.
 *
 * This class was ported from the Opsbears Foundation library with authorization.
 */
class Collection extends ArrayObject {
	/**
	 * Throws a \InvalidArgumentException, if $offset is not an integer
	 *
	 * @param int $offset
	 *
	 * @throws InvalidTypeException
	 */
	final protected function keyCheck($offset) {
		if (\is_int($offset) || \is_float($offset)) {
			return;
		}
		if (
			\is_bool($offset) ||
			\is_string($offset) ||
			(\is_object($offset) && \method_exists($offset, '__toString'))
		) {
			if (\is_bool($offset)) {
				return;
			} elseif (\preg_match('/^(\-|\+|)[0-9]+(|\.[0-9]+)$/D', $offset)) {
				return;
			}
		}
		throw new InvalidTypeException($offset, 'integer-convertible');
	}

	/**
	 * @param mixed $element
	 * @return int
	 */
	public function push($element) {
		return \array_push($this->data, $element);
	}

	/**
	 * @return mixed
	 * @throws \OutOfBoundsException
	 */
	public function pop() {
		$result = \array_pop($this->data);
		if (\is_null($result)) {
			throw new OutOfBoundsException();
		}
		return $result;
	}

	/**
	 * @param mixed $element
	 * @return int
	 */
	public function unshift($element) {
		return \array_unshift($this->data, $element);
	}

	/**
	 * @return mixed
	 * @throws OutOfBoundsException
	 */
	public function shift() {
		$result = \array_shift($this->data);
		if (\is_null($result)) {
			throw new OutOfBoundsException();
		}
		return $result;
	}
}
