<?php

namespace Ixolit\Dislo\CDE;


use Ixolit\Dislo\CDE\Exceptions\CookieNotSetException;
use Ixolit\Dislo\CDE\Interfaces\RequestAPI;
use Ixolit\Dislo\CDE\Interfaces\ResponseAPI;

/**
 * Class CDECookieCache
 *
 * @package Ixolit\Dislo\CDE
 */
class CDECookieCache {

    const COOKIE_TIMEOUT_THIRTY_DAYS = 2592000;

    /** @var CDECookieCache */
    private static $instance;

    /** @var RequestApi */
    private $requestApi;

    /** @var ResponseApi */
    private $responseApi;

    /** @var array */
    private $cookieCache;

    protected function __construct() {
        $this->requestApi = CDE::getRequestAPI();
        $this->responseApi = CDE::getResponseAPI();
        $this->cookieCache = [];
    }

    protected function __clone() {}

    /**
     * @return CDECookieCache
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $cookieName
     * @param string $value
     * @param int    $cookieTimeout
     *
     * @return $this
     */
    public function write($cookieName, $value, $cookieTimeout = self::COOKIE_TIMEOUT_THIRTY_DAYS) {
        return $this
            ->setCookieValue($cookieName, $value)
            ->storeCookieData($cookieName, $value, $cookieTimeout);
    }

    /**
     * @param string $cookieName
     *
     * @return string|null
     */
    public function read($cookieName) {
        $cookieValue = $this->getCookieValue($cookieName);

        if ($cookieValue === null) {
            $cookieValue = $this->restoreCookieData($cookieName);

            if ($cookieValue) {
                $this->setCookieValue($cookieName, $cookieValue);
            }
        }

        return $cookieValue;
    }

    /**
     * @param string $cookieName
     * @param int    $cookieTimeout
     *
     * @return $this
     */
    public function renew($cookieName, $cookieTimeout = self::COOKIE_TIMEOUT_THIRTY_DAYS) {
        return $this->write($cookieName, $this->read($cookieName), $cookieTimeout);
    }

    /**
     * @param string $cookieName
     *
     * @return $this
     */
    public function delete($cookieName) {
        return $this->write($cookieName, null, -1);
    }

    /**
     * @param string $cookieName
     *
     * @return string
     */
    public function consume($cookieName) {
        $cookieValue = $this->read($cookieName);

        $this->delete($cookieName);

        return $cookieValue;
    }

    /**
     * @return RequestApi
     */
    protected function getRequestApi() {
        return $this->requestApi;
    }

    /**
     * @return ResponseAPI
     */
    protected function getResponseApi() {
        return $this->responseApi;
    }

    /**
     * @param string $cookieName
     * @param string $cookieValue
     *
     * @return $this
     */
    protected function setCookieValue($cookieName, $cookieValue) {
        $this->cookieCache[$cookieName] = $cookieValue;

        return $this;
    }

    /**
     * @param string $cookieName
     *
     * @return string|null
     */
    protected function getCookieValue($cookieName) {
        if (!isset($this->cookieCache[$cookieName])) {
            return null;
        }

        return $this->cookieCache[$cookieName];
    }

    /**
     * @param string $cookieName
     * @param string $cookieValue
     * @param int    $cookieTimeout
     *
     * @return $this
     */
    protected function storeCookieData($cookieName, $cookieValue, $cookieTimeout) {
        $this->getResponseApi()->setCookie($cookieName, $cookieValue, $cookieTimeout);

        return $this;
    }

    /**
     * @param string $cookieName
     *
     * @return null|string
     */
    protected function restoreCookieData($cookieName) {
        try {
            $cookieValue = $this->getRequestApi()->getCookie($cookieName)->getValue();
        } catch (CookieNotSetException $e) {
            $cookieValue = null;
        }

        return $cookieValue;
    }

}