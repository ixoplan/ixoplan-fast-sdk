<?php

namespace Ixolit\Dislo\CDE\Ixoplan;

/**
 * Class AbstractController
 * @package Ixolit\Dislo\CDE\Ixoplan
 */
class AbstractController {

    /**
     * @var Request
     */
    private $request;

    /**
     * AbstractController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

}