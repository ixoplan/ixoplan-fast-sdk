<?php

namespace Ixolit\CDE\Context\Custom;

use Ixolit\CDE\Context\Page;
use Ixolit\Dislo\CDE\CDEDisloClient;
use Ixolit\Dislo\CDE\Context\PageRedirectorRequest;
use Ixolit\Dislo\CDE\Context\PageRedirectorResult;
use Ixolit\Dislo\Redirector\Base\RedirectorRequestInterface;
use Ixolit\Dislo\Redirector\Base\RedirectorResultInterface;

/**
 * Class PageCustom
 *
 * @package Ixolit\Dislo\CDE\Context
 */
final class PageCustom implements PageCustomInterface {

    /**
     * @var Page
     */
    private $page;

    /** @var CDEDisloClient */
    private $cdeDislolient;

    /**
     * @param Page $page
     *
     * @return $this
     */
    public function setPage(Page $page) {
        $this->page = $page;

        return $this;
    }

    /**
     * @return Page
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * @return $this
     */
    public function doPrepare() {
        $this->doRedirects();

        return $this;
    }

    protected function doRedirects() {
        try {
            $config = $this->getCdeDisloClient()->miscGetRedirectorConfiguration();
            if ($config && $config->getRedirector()) {
                $redirector = $config->getRedirector();
                if ($redirector) {
                    $redirector->evaluate($this->getRedirectorRequest(), $this->getRedirectorResult());
                }
            }
        }
        catch (\Exception $e) {
            // ignore errors
        }
    }

    /**
     * @return CDEDisloClient
     */
    protected function newCdeDisloClient() {
        return new CDEDisloClient();
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
     * @return RedirectorRequestInterface
     */
    protected function getRedirectorRequest() {
        return new PageRedirectorRequest($this->getPage());
    }

    /**
     * @return RedirectorResultInterface
     */
    protected function getRedirectorResult() {
        return new PageRedirectorResult($this->getPage());
    }
}