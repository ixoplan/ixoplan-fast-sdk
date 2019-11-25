<?php

namespace Ixolit\Dislo\CDE\EventEngine\Event;

/**
 * Interface EventInterface
 * @package Ixolit\Dislo\CDE\EventEngine
 */
interface EventInterface {

    /**
     * @return string
     */
    public function getName();

    /**
     * @param EventContext $context
     * @return $this
     */
    public function setContext(EventContext $context);

    /**
     * @return EventContext
     */
    public function getContext();

}