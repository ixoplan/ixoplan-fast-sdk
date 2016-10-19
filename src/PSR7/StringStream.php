<?php

namespace Ixolit\Dislo\CDE\PSR7;

use Psr\Http\Message\StreamInterface;

class StringStream implements StreamInterface {
	/**
	 * @var string
	 */
	private $data;

	/**
	 * @var int
	 */
	private $position = 0;

	/**
	 * @param string $data
	 */
	public function __construct($data) {
		$this->data = $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString() {
		return $this->data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function close() {
	}

	/**
	 * {@inheritdoc}
	 */
	public function detach() {
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSize() {
		return \strlen($this->data);
	}

	/**
	 * {@inheritdoc}
	 */
	public function tell() {
		return $this->position;
	}

	/**
	 * {@inheritdoc}
	 */
	public function eof() {
		return ($this->position >= \strlen($this->data) - 1);
	}

	/**
	 * {@inheritdoc}
	 */
	public function isSeekable() {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function seek($offset, $whence = SEEK_SET) {
		switch ($whence) {
			case SEEK_END:
				$newPosition = \strlen($this->data) - 1 + $offset;
				break;
			case SEEK_CUR:
				$newPosition = $this->position + $offset;
				break;
			case SEEK_SET:
				$newPosition = $offset;
				break;
			default:
				throw new \RuntimeException('Invalid value for $whence: ' . $whence);
		}

		if ($newPosition < 0 || $newPosition > \strlen($this->data) -1) {
			throw new \RuntimeException('Invalid position: ' . $newPosition);
		}

		$this->position = $newPosition;
	}

	/**
	 * {@inheritdoc}
	 */
	public function rewind() {
		$this->position = 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isWritable() {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function write($string) {
		$this->data = \substr($this->data, 0, $this->position + 1) .
			$string .
			\substr($this->data, $this->position + 1 + strlen($string));
		return \strlen($string);
	}

	/**
	 * {@inheritdoc}
	 */
	public function isReadable() {
		return true;
	}

	/**
	 * Read data from the stream.
	 *
	 * @param int $length Read up to $length bytes from the object and return
	 *                    them. Fewer than $length bytes may be returned if underlying stream
	 *                    call returns fewer bytes.
	 *
	 * @return string Returns the data read from the stream, or an empty string
	 *     if no bytes are available.
	 * @throws \RuntimeException if an error occurs.
	 */
	public function read($length) {
		$result = \substr($this->data, $this->position, $length);
		$this->seek(\strlen($result), SEEK_CUR);
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getContents() {
		$data = \substr($this->data, $this->position);
		$this->seek(0, SEEK_END);
		return $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMetadata($key = null) {
		$data = [
			'timed_out' => false,
			'blocked' => false,
			'eof' => $this->eof(),
			'unread_bytes' => \strlen($this->data) - $this->position + 1,
			'stream_type' => 'string',
			'wrapper_type' => 'string://',
			'wrapper_data' => [],
			'mode' => 'c+',
			'seekable' => true,
			'uri' => 'data://text/plain;base64,' . \base64_encode($this->data)
		];

		if ($key) {
			if (isset($data[$key])) {
				return $data[$key];
			} else {
				return null;
			}
		} else {
			return $data;
		}
	}
}