<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

class Page {
	/**
	 * @var string
	 */
	private $pageUrl;

	/**
	 * @var string
	 */
	private $pagePath;

	/**
	 * @param string $pageUrl
	 * @param string $pagePath
	 */
	public function __construct($pageUrl, $pagePath) {
		$this->pageUrl  = $pageUrl;
		$this->pagePath = $pagePath;
	}

	/**
	 * @return string
	 */
	public function getPageUrl() {
		return $this->pageUrl;
	}

	/**
	 * @return string
	 */
	public function getPagePath() {
		return $this->pagePath;
	}
}
