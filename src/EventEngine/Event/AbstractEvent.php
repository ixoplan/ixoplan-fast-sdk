<?php

namespace Ixolit\Dislo\CDE\EventEngine\Event;

/**
 * Class AbstractEvent
 * @package Ixolit\Dislo\CDE\EventEngine\Event
 */
abstract class AbstractEvent implements EventInterface {

    /**
     * @var EventContext
     */
    private $context;

    public function getName() {
        return substr(strrchr(get_called_class(), "\\"), 1);
    }

    /**
     * @return \Ixolit\Dislo\CDE\EventEngine\Thread | null
     */
    public function getThread() {
        return $this->getContext() ? $this->getContext()->getThread() : null;
    }

    /**
     * @param EventContext $context
     * @return $this
     */
    public function setContext(EventContext $context) {
        $this->context = $context;
        return $this;
    }

    /**
     * @return EventContext
     */
    public function getContext() {
        return $this->context;
    }

}