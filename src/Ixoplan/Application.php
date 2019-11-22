<?php

namespace Ixolit\Dislo\CDE\Ixoplan;

use Ixolit\CDE\CDE;
use Ixolit\CDE\Interfaces\FilesystemAPI;
use Ixolit\CDE\Interfaces\KVSAPI;
use Ixolit\CDE\Interfaces\MetaAPI;
use Ixolit\Dislo\CDE\CDEDisloClient;

/**
 * Ixoplan might call the CDE for specific tasks, like rendering emails, rendering event engine actions, etc.
 * Class Application
 */
class Application {

	/** @var self */
	private static $instance;

	/** @var FilesystemAPI */
	private $filesystemAPI;

	/** @var MetaAPI */
	private $metaAPI;

	/** @var KVSAPI */
	private $kvsAPI;

	/** @var CDEDisloClient */
	private $cdeDislolient;

	protected function __construct() {}

	private function __clone() {}

	/**
	 * @return static
	 */
	public static function get() {

		if (!isset(self::$instance)) {
			self::$instance = new static();
		}

		return self::$instance;
	}

    /**
	 * @return CDEDisloClient
	 */
	protected function newCdeDisloClient() {
		return new CDEDisloClient(null, false);
	}

	/**
	 * @return CDEDisloClient
	 */
	public function getCdeDisloClient() {
		if (!isset($this->cdeDislolient)) {
			$this->cdeDislolient = $this->newCdeDisloClient();
		}

		return $this->cdeDislolient;
	}

	/**
	 * @return FilesystemAPI
	 */
	protected function newFilesystemAPI() {
		return CDE::getFilesystemAPI();
	}

	/**
	 * @return MetaAPI
	 */
	protected function newMetaAPI() {
		return CDE::getMetaAPI();
	}

	/**
	 * @return KVSAPI
	 */
	protected function newKvsAPI() {
		return CDE::getKVSAPI();
	}

	/**
	 * @return FilesystemAPI
	 */
	public function getFilesystemAPI() {

		if (!isset($this->filesystemAPI)) {
			$this->filesystemAPI = $this->newFilesystemAPI();
		}

		return $this->filesystemAPI;
	}

	/**
	 * @return MetaAPI
	 */
	public function getMetaAPI() {

		if (!isset($this->metaAPI)) {
			$this->metaAPI = $this->newMetaAPI();
		}

		return $this->metaAPI;
	}

	/**
	 * @return KVSAPI
	 */
	public function getKvsAPI() {
		if (!isset($this->kvsAPI)) {
			$this->kvsAPI = $this->newKvsAPI();
		}

		return $this->kvsAPI;
	}

	public function init() {
        //do nothing
	}

    /**
     * @param Request $request
     */
    public function run(Request $request) {

        $this->init();

        try {
            echo Router::route($request);
            exit();
        } catch (RequestExceptionWithContent $e) {
            echo Response::createWithError($e->getCode(), $e->getMessage(), $e->getContent());
            exit(1);
        } catch (\Exception $e) {
            echo Response::createWithError($e->getCode(), $e->getMessage());
            exit(1);
        }

    }

}

