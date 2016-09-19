<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

class BreadcrumbEntry {
	/**
	 * @var string
	 */
	private $pagePath;
	/**
	 * @var string
	 */
	private $url;
	/**
	 * @var string
	 */
	private $title;

	/**
	 * @param string $pagePath
	 * @param string $url
	 * @param string $title
	 */
	public function __construct($pagePath, $url, $title) {
		$this->pagePath = $pagePath;
		$this->url      = $url;
		$this->title    = $title;
	}

	/**
	 * @return string
	 */
	public function getPagePath() {
		return $this->pagePath;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
}