<?php

namespace Ixolit\Dislo\CDE\EventEngine\Event\Billing;

use Ixolit\Dislo\CDE\EventEngine\Event\AbstractEvent;
use Ixolit\Dislo\CDE\EventEngine\WorkingObject\Generic;

/**
 * Class SystemInitiatedChargeFailed
 *
 * @package Ixolit\Dislo\CDE\EventEngine\Event\Billing
 */
class SystemInitiatedChargeFailed extends AbstractEvent {

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

    /**
     * @return \Ixolit\Dislo\WorkingObjects\Flexible
     */
    public function getFlexible() {
        return $this->getContext()->getFlexible();
    }

    /**
     * @return Generic
     */
    public function getAccountingAccount() {
        return $this->getContext()->getAccountingAccount();
    }

    /**
     * @return Generic
     */
    public function getRetryInfo() {
        return $this->getContext()->getRetryInfo();
    }

}