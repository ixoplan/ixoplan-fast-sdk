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
	private $effectiveVhost;

	/**
	 * @param string $vhost
	 * @param        $effectiveVhost
	 * @param string $name
	 */
	public function __construct($vhost, $effectiveVhost, $name) {
		$this->vhost = $vhost;
		$this->name  = $name;
		$this->effectiveVhost = $effectiveVhost;
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return '/vhosts/' . \urlencode($this->getEffectiveVhost()) . '/layouts/' . \urlencode($this->getName());
	}

	/**
	 * @return string
	 */
	public function getVhost() {
		return $this->vhost;
	}

	/**
	 * @return mixed
	 */
	public function getEffectiveVhost() {
		return $this->effectiveVhost;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
}
