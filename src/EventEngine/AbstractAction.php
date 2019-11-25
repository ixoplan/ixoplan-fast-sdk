<?php

namespace Ixolit\Dislo\CDE\EventEngine;

use Ixolit\Dislo\CDE\EventEngine\Event\AbstractEvent;
use Ixolit\Dislo\CDE\EventEngine\Event\EventInterface;

/**
 * Class AbstractAction
 */
abstract class AbstractAction implements ActionInterface {

    /**
     * @return string
     */
    public function getName(){
        return substr(strrchr(get_called_class(), "\\"), 1);
    }

    /**
     * The string will be used as grouping identifier for displaying the various actions on the event engine
     * @return string
     */
    public function getGroup(){
        return self::GROUP_GENERAL;
    }

    public function toArray() {
        return array(
            'class' => get_called_class(),
            'name' => $this->getName(),
            'group' => $this->getGroup(),
        );
    }

    /**
     * @param array $availableWorkingObjects
     * @param AbstractEvent|null $event
     * @return bool
     */
    public function hasRequiredWorkingObjects(array $availableWorkingObjects=array(), AbstractEvent $event=null) {
        return true;
    }

    /**
     * @param EventInterface $event
     * @throws \Exception
     * @return ResultActionCollection
     */
    abstract public function execute(EventInterface $event);

}