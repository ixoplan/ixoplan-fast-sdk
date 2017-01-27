<?php

namespace Ixolit\Dislo\CDE;


/**
 * Class CDETemporaryDataStorage
 *
 * @package Ixolit\Dislo\CDE
 */
class CDETemporaryDataStorage {

    const DATA_STORAGE_TIMEOUT_THIRTY_DAYS = 2592000;
    const COOKIE_NAME_TEMPORARY_DATA = 'temporary-data';

    /** @var CDETemporaryDataStorage */
    private static $instance;

    /** @var array */
    private $dataStorage;

    /** @var int */
    private $dataStorageTimeout;

    /**
     * @param int $dataStorageTimeout
     */
    protected function __construct($dataStorageTimeout = self::DATA_STORAGE_TIMEOUT_THIRTY_DAYS) {
        $this->dataStorageTimeout = $dataStorageTimeout;

        $this->dataStorage = $this->restoreDataStorage();
    }

    protected function __clone() {}

    /**
     * @param int $dataStorageTimeout
     *
     * @return $this
     */
    public static function getInstance($dataStorageTimeout = self::DATA_STORAGE_TIMEOUT_THIRTY_DAYS) {
        if (self::$instance === null) {
            self::$instance = new self($dataStorageTimeout);
        }

        return self::$instance;
    }

    /**
     * @param string $dataKey
     * @param mixed $dataValue
     *
     * @return CDETemporaryDataStorage
     */
    public function write($dataKey, $dataValue) {
        return $this->setDataStorageValue($dataKey, $dataValue);
    }

    /**
     * @param string $dataKey
     *
     * @return mixed|null
     */
    public function read($dataKey) {
        return $this->getDataStorageValue($dataKey);
    }

    /**
     * @param string $dataKey
     *
     * @return $this
     */
    public function delete($dataKey) {
        unset($this->dataStorage[$dataKey]);

        return $this->storeDataStorage();
    }

    /**
     * @param string $dataKey
     *
     * @return mixed|null
     */
    public function consume($dataKey) {
        $dataValue = $this->read($dataKey);

        $this->delete($dataKey);

        return $dataValue;
    }

    /**
     * @param string $dataKey
     * @param mixed $dataValue
     *
     * @return $this
     */
    protected function setDataStorageValue($dataKey, $dataValue) {
        $this->dataStorage[$dataKey] = $dataValue;

        return $this->storeDataStorage();
    }

    /**
     * @param string $dataKey
     *
     * @return mixed|null
     */
    protected function getDataStorageValue($dataKey) {
        if (!isset($this->dataStorage[$dataKey])) {
            return null;
        }

        return $this->dataStorage[$dataKey];
    }

    /**
     * @return $this
     */
    protected function storeDataStorage() {
        $encodedDataStorage = \base64_encode(\json_encode($this->dataStorage));

        CDECookieCache::getInstance()->write(
            self::COOKIE_NAME_TEMPORARY_DATA,
            $encodedDataStorage,
            $this->dataStorageTimeout
        );

        return $this;
    }

    /**
     * @return array
     */
    protected function restoreDataStorage() {
        $dataStorage = CDECookieCache::getInstance()->read(self::COOKIE_NAME_TEMPORARY_DATA);

        if (empty($dataStorage)) {
            return [];
        }

        $dataStorage = \json_decode(\base64_decode($dataStorage), true);

        if (!\is_array($dataStorage)) {
            return [];
        }

        return $dataStorage;
    }

}