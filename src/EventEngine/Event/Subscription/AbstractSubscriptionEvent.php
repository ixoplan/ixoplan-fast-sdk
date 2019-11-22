<?php


namespace Ixolit\Dislo\CDE\EventEngine\Event\Subscription;

use Ixolit\Dislo\CDE\EventEngine\Event\AbstractEvent;
use Ixolit\Dislo\WorkingObjects\Subscription;
use Ixolit\Dislo\WorkingObjects\User;

/**
 * Class AbstractSubscriptionEvent
 * @package Ixolit\Dislo\CDE\EventEngine\Event\Subscription
 */
abstract class AbstractSubscriptionEvent extends AbstractEvent {

    /**
     * @return User
     */
    public function getUser() {
        return $this->getContext()->getUser();
    }

    /**
     * @return Subscription
     */
    public function getSubscription() {
        return $this->getContext()->getSubscription();
    }

}