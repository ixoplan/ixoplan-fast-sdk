<?php

namespace Ixolit\Dislo\CDE\Interfaces;

use InvalidArgumentException;

/**
 * This interface can be used for a replacement of the built-in ArrayObject class to pass array-compatible objects
 * to functions. All functions using the interoperability framework SHOULD accept both the ArrayObjectInterface and
 * the PHP-native ArrayObject.
 *
 * Type documentation using this class should be made as `array|ArrayObjectInterface|\ArrayObject`
 */
interface ArrayObjectInterface extends \Iterator, \ArrayAccess, \Countable {
	/**
	 * Add a single element
	 *
	 * @param mixed $element
	 *
	 * @return ArrayObjectInterface
	 */
	public function add($element);

	/**
	 * Add all elements from an other collection to this one.
	 *
	 * @param ArrayObjectInterface|array|\Iterator $other
	 *
	 * @return ArrayObjectInterface
	 *
	 * @throws \InvalidArgumentException
	 */
	public function addAll($other);

	/**
	 * Deletes all elements.
	 */
	public function clear();

	/**
	 * Checks if an element is contained here.
	 *
	 * @param mixed $element
	 *
	 * @return bool
	 */
	public function contains($element);

	/**
	 * Checks all elements in a Collection if they are contained in this
	 * ArrayObject.
	 *
	 * @param ArrayObjectInterface|array|\Iterator $other
	 *
	 * @return bool
	 *
	 * @throws InvalidArgumentException
	 */
	public function containsAll($other);

	/**
	 * Removes an element by reference.
	 *
	 * @param mixed $element
	 *
	 * @return bool if the data has changed.
	 */
	public function remove($element);

	/**
	 * Remove all elements contained in an other ArrayObject
	 *
	 * @param ArrayObjectInterface $other
	 *
	 * @return ArrayObjectInterface
	 */
	public function removeAll($other);

	/**
	 * Retains all elements contained in a different ArrayObjectInterface
	 *
	 * @param ArrayObjectInterface $other
	 *
	 * @return ArrayObjectInterface
	 *
	 * @throws InvalidArgumentException
	 */
	public function retainAll($other);
}