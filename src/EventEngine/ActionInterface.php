<?php

namespace Ixolit\Dislo\CDE\EventEngine;

use Ixolit\Dislo\CDE\EventEngine\Event\AbstractEvent;
use Ixolit\Dislo\CDE\EventEngine\Event\EventInterface;

/**
 * Interface ActionInterface
 */
interface ActionInterface {

    const GROUP_GENERAL = 'general';

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getGroup();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @param array $availableWorkingObjects
     * @param AbstractEvent|null $event
     * @return bool
     */
    public function hasRequiredWorkingObjects(array $availableWorkingObjects=array(), AbstractEvent $event=null);

    /**
     * @param EventInterface $event
     * @return ActionResult
     */
    public function execute(EventInterface $event);

}