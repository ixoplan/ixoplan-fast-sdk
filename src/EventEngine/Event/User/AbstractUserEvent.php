<?php


namespace Ixolit\Dislo\CDE\EventEngine\Event\User;

use Ixolit\Dislo\CDE\EventEngine\Event\AbstractEvent;
use Ixolit\Dislo\WorkingObjects\User;

/**
 * Class AbstractUserEvent
 * @package Ixolit\Dislo\CDE\EventEngine\Event\User
 */
abstract class AbstractUserEvent extends AbstractEvent {

    /**
     * @return User
     */
    public function getUser() {
        return $this->getContext()->getUser();
    }

}