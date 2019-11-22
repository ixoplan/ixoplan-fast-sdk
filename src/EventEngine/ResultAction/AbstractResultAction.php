<?php

namespace Ixolit\Dislo\CDE\EventEngine\ResultAction;

/**
 * Class AbstractResultAction
 */
abstract class AbstractResultAction implements ResultActionInterface {

    /**
     * @return static
     */
    public static function create() {
        return new static();
    }

    /**
     * @return string
     */
    public function getName() {
        return substr(strrchr(get_called_class(), "\\"), 1);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'name' => $this->getName(),
            'data' => $this->getData()
        );
    }

    /**
     * @return array
     */
    public function getData(){
        return get_object_vars($this);
    }
}