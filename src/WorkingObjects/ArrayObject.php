<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

use InvalidArgumentException;
use Ixolit\Dislo\CDE\Interfaces\ArrayObjectInterface;
use OutOfBoundsException;

/**
 *
 *
 * This class was ported from the Opsbears Foundation library with authorization.
 */
abstract class ArrayObject implements ArrayObjectInterface {
	/**
	 * Stores the collection elements
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Throws a \InvalidArgumentException, if $offset is not of the required type
	 *
	 * @param mixed $offset
	 *
	 * @throws \InvalidArgumentException if $offset is not of the required type
	 */
	abstract protected function keyCheck($offset);

	/**
	 * This function checks, if the ArrayObject subclass may contain the given
	 * type.
	 *
	 * @param mixed $element
	 *
	 * @throws InvalidArgumentException if the Collection cannot contain this element
	 *    type.
	 */
	protected function typeCheck($element) {

	}

	/**
	 * Create a new data object. If $data is an array or an \Iterator implementation, every value is added separately.
	 *
	 * @param array|\Iterator|\IteratorAggregate|mixed $data
	 */
	public function __construct($data = null) {
		if (\is_array($data) || $data instanceof \Iterator || $data instanceof \IteratorAggregate) {
			foreach ($data as $key => $value) {
				$this->offsetSet($key, $value);
			}
		} else {
			if (!\is_null($data)) {
				$this->offsetSet(null, $data);
			}
		}
	}

	/**
	 * Compares the contents of this array object to another array or array object
	 *
	 * @param mixed $other
	 *
	 * @return bool
	 */
	public function equals($other) {
		if (\is_array($other) || ($other instanceof \ArrayAccess && $other instanceof \Countable)) {
			if ($this->count() !== \count($other)) {
				return false;
			}
			foreach ($this->data as $key => $value) {
				if (!isset($other[$key])) {
					return false;
				}
				if ($other[$key] !== $value) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Counts the elements in this collection. Implemented as required by the
	 * Countable interface.
	 *
	 * @return int
	 */
	public function count() {
		return \count($this->data);
	}

	/**
	 * Returns if an offset exists in this collection. Implemented as required
	 * by the ArrayAccess interface.
	 *
	 * @param int|string $offset the offset to check
	 *
	 * @return bool
	 */
	public function offsetExists($offset) {
		$this->keyCheck($offset);
		if (\array_key_exists($offset, $this->data)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns an element in this collection. Implemented as required by the
	 * ArrayAccess interface.
	 *
	 * @param int|string $offset the offset to fetch
	 *
	 * @return mixed
	 *
	 * @throws \OutOfBoundsException if the value $offset is not available
	 */
	public function offsetGet($offset) {
		$this->keyCheck($offset);
		if ($this->offsetExists($offset)) {
			$data = &$this->data[$offset];
			return $data;
		} else {
			throw new OutOfBoundsException($offset);
		}
	}

	/**
	 * Set an element in this Collection. Implemented as required by the
	 * ArrayAccess interface.
	 *
	 * @param int|string $offset
	 * @param mixed      $value
	 */
	public function offsetSet($offset, $value) {
		$this->typeCheck($value);
		if (\is_null($offset)) {
			$this->data[] = $value;
		} else {
			$this->keyCheck($offset);
			$this->data[$offset] = $value;
		}
	}

	/**
	 * This function unsets a given offset from a Collection. Implemented
	 * as required by the ArrayAccess interface.
	 *
	 * @param int $offset
	 *
	 * @return $this
	 * @throws OutOfBoundsException
	 */
	public function offsetUnset($offset) {
		$this->keyCheck($offset);
		if ($this->offsetExists($offset)) {
			unset($this->data[$offset]);

			return $this;
		} else {
			throw new OutOfBoundsException($offset);
		}
	}

	/**
	 * Returns the current element in the Collection. Implemented as required
	 * by the Iterator interface.
	 *
	 * @return mixed
	 */
	public function current() {
		if ($this->valid()) {
			return \current($this->data);
		} else {
			return null;
		}
	}

	/**
	 * Returns the key of the current element. Returns the key of the current
	 * Collection element. Implemented as required by the Iterator interface.
	 *
	 * @return int|string
	 */
	public function key() {
		return \key($this->data);
	}

	/**
	 * Moves the pointer to the next element.
	 * Implemented as required by the Iterator interface.
	 */
	public function next() {
		each($this->data);
	}

	/**
	 * Sets the internal pointer of this collection to the first element.
	 * Implemented as required by the Iterator interface.
	 *
	 * @return bool false if the Collection is empty.
	 */
	public function rewind() {
		if (\reset($this->data)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks if the Collection internal pointer is on a valid element.
	 * Implemented as required by the Iterator interface.
	 *
	 * @return bool
	 */
	public function valid() {
		if ($this->key() === null) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Add a single element
	 *
	 * @param mixed $element
	 *
	 * @return $this
	 */
	public function add($element) {
		$this->typeCheck($element);
		$this[] = $element;

		return $this;
	}

	/**
	 * Add all elements from an other collection to this one.
	 *
	 * @param ArrayObjectInterface|array|\Iterator $other
	 *
	 * @return $this
	 *
	 * @throws InvalidArgumentException
	 */
	public function addAll($other) {
		if (!$other instanceof \Iterator && !\is_array($other)) {
			throw new InvalidArgumentException($other, 'array or array-convertible');
		}

		/**
		 * Add a type check to return in a consistent manner, if it fails.
		 */
		foreach ($other as $element) {
			$this->typeCheck($element);
		}
		foreach ($other as $element) {
			$this[] = $element;
		}

		return $this;
	}

	/**
	 * Deletes all elements.
	 */
	public function clear() {
		$this->data = array();
	}

	/**
	 * Checks if an element is contained here.
	 *
	 * @param mixed $element
	 *
	 * @return bool
	 */
	public function contains($element) {
		return \in_array($element, $this->data, true);
	}

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
	public function containsAll($other) {
		if (!$other instanceof \Iterator && !\is_array($other)) {
			throw new InvalidArgumentException($other, 'array or array-convertible');
		}

		foreach ($other as $element) {
			if (!$this->contains($element)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Removes an element by reference.
	 *
	 * @param mixed $element
	 *
	 * @return bool if the data has changed.
	 */
	public function remove($element) {
		$key = \array_search($element, $this->data, true);
		if ($key === false) {
			return false;
		} else {
			unset($this->data[$key]);

			return true;
		}
	}

	/**
	 * Remove all elements contained in an other ArrayObject
	 *
	 * @param array|\Iterator $other
	 *
	 * @return $this
	 *
	 * @throws InvalidArgumentException
	 */
	public function removeAll($other) {
		if (!$other instanceof \Iterator && !\is_array($other)) {
			throw new InvalidArgumentException($other, 'array or array-convertible');
		}

		foreach ($other as $elements) {
			$this->remove($elements);
		}

		return $this;
	}

	/**
	 * Retains all elements contained in a different collection
	 *
	 * @param \Iterator|array $other
	 *
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function retainAll($other) {
		if (!$other instanceof \Iterator && !\is_array($other)) {
			throw new InvalidArgumentException($other, 'array or array-convertible');
		}

		foreach ($this as $key => $value) {
			if (\is_array($other) && !\in_array($value, $other, true)) {
				unset($this[$key]);
			} else if ($other instanceof ArrayObjectInterface && !$other->contains($value)) {
				unset($this[$key]);
			} else {
				$found = false;
				foreach ($other as $otherKey => $otherValue) {
					if ($otherValue === $value) {
						$found = true;
						break;
					}
				}
				if (!$found) {
					unset($this[$key]);
				}
			}
		}

		return $this;
	}

	/**
	 * Returns the array object contents as an array
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->data;
	}
}