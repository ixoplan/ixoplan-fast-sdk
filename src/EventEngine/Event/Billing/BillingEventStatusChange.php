<?php

namespace Ixolit\Dislo\CDE\EventEngine\Event\Billing;

use Ixolit\Dislo\CDE\EventEngine\Event\AbstractEvent;

/**
 * Class BillingEventStatusChange
 *
 * @package Ixolit\Dislo\CDE\EventEngine\Event\Billing
 */
class BillingEventStatusChange extends AbstractEvent {

    /**
     * @return \Ixolit\Dislo\WorkingObjects\User
     */
    public function getUser() {
        return $this->getContext()->getUser();
    }

    /**
     * @return \Ixolit\Dislo\WorkingObjects\BillingEvent
     */
    public function getBillingEvent() {
        return $this->getContext()->getBillingEvent();
    }

    /**
     * @return \Ixolit\Dislo\CDE\EventEngine\WorkingObject\NotificationData
     */
    public function getNotificationData() {
        return $this->getContext()->getNotificationData();
    }
    
}
