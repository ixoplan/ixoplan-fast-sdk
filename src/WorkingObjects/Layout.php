<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

class Layout {
	/**
	 * @var string
	 */
	private $vhost;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param string $vhost
	 * @param string $name
	 */
	public function __construct($vhost, $name) {
		$this->vhost = $vhost;
		$this->name  = $name;
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return '/vhosts/' . \urlencode($this->getVhost()) . '/layouts/' . \urlencode($this->getName());
	}

	/**
	 * @return string
	 */
	public function getVhost() {
		return $this->vhost;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
}