<?php

namespace Ixolit\Dislo\CDE\EventEngine;

use Ixolit\Dislo\CDE\EventEngine\ResultAction\ResultActionInterface;

/**
 * Class ResultActionCollection
 */
class ResultActionCollection {

    /**
     * @var ResultActionInterface[]
     */
    private $actions = [];

    /**
     * @param ResultActionInterface $action
     * @return $this
     */
    public function addAction(ResultActionInterface $action) {
        $this->actions[] = $action;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray() {
        return array_map(function(ResultActionInterface $action) {
            return $action->toArray();
        }, $this->actions);
    }

    /**
     * @return ResultActionCollection
     */
    public static function createEmpty() {
        return new self();
    }


}