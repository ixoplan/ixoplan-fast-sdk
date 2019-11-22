<?php

namespace Ixolit\Dislo\CDE\EventEngine\Event;

/**
 * Class GenericEvent
 * @package Ixolit\Dislo\CDE\EventEngine\Event
 */
class GenericEvent extends AbstractEvent {

    /**
     * @var string
     */
    private $name;

    /**
     * GenericEvent constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
    
    /**
     * @return false|string
     */
    public function getName() {
        return $this->name;
    }


}