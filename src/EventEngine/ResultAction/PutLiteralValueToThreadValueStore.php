<?php

namespace Ixolit\Dislo\CDE\EventEngine\ResultAction;

/**
 * Class PutLiteralValueToThreadValueStore
 */
class PutLiteralValueToThreadValueStore extends AbstractResultAction {

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var bool
     */
    protected $skipIfEmpty=false;

    /**
     * PutLiteralValueToThreadValueStore constructor.
     * @param $key
     * @param $value
     * @param bool $skipIfEmpty
     */
    public function __construct($key, $value, $skipIfEmpty=false) {
        $this->key         = $key;
        $this->value       = $value;
        $this->skipIfEmpty = (bool) $skipIfEmpty;
    }


}