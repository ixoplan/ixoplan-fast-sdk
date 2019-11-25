<?php

namespace Ixolit\Dislo\CDE\EventEngine\ResultAction;

/**
 * Class PutValueToNotificationData
 */
class PutValueToNotificationData extends AbstractResultAction {

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
    protected $skipIfAlreadySet=true;

    /**
     * PutLiteralValueToThreadValueStore constructor.
     * @param $key
     * @param $value
     * @param bool $skipIfAlreadySet
     */
    public function __construct($key, $value, $skipIfAlreadySet=false) {
        $this->key         = $key;
        $this->value       = $value;
        $this->skipIfAlreadySet = (bool) $skipIfAlreadySet;
    }


}