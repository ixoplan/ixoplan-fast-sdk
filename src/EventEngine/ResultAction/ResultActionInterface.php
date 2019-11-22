<?php

namespace Ixolit\Dislo\CDE\EventEngine\ResultAction;

/**
 * Interface ResultActionInterface
 */
interface ResultActionInterface {

    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getData();

    /**
     * @return array
     */
    public function toArray();

}