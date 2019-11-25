<?php

namespace Ixolit\Dislo\CDE\Ixoplan;

use JsonSerializable;

/**
 * Class RequestExceptionWithContent
 * @package Ixolit\Dislo\CDE\Ixoplan
 */
class RequestExceptionWithContent extends \Exception
{

    /**
     * @var JsonSerializable|array
     */
    private $content;

    /**
     * @return array|JsonSerializable
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param array|JsonSerializable $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

}