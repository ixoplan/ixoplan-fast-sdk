<?php

namespace Ixolit\Dislo\CDE\EventEngine\Event\Billing;

use Ixolit\Dislo\CDE\EventEngine\Event\AbstractEvent;

/**
 * Class SystemInitiatedInvoiceChargeMightFail
 *
 * @package Ixolit\Dislo\CDE\EventEngine\Event\Billing
 */
class SystemInitiatedInvoiceChargeMightFail extends AbstractEvent {

    /**
     * @return \Ixolit\Dislo\WorkingObjects\User
     */
    public function getUser() {
        return $this->getContext()->getUser();
    }

    /**
     * @return \Ixolit\Dislo\WorkingObjects\Flexible
     */
    public function getFlexible() {
        return $this->getContext()->getFlexible();
    }

    /**
     * @return \Ixolit\Dislo\CDE\EventEngine\SubscriptionCollection
     */
    public function getSubscriptionCollection() {
        return $this->getContext()->getSubscriptionCollection();
    }

    /**
     * @return \Ixolit\Dislo\CDE\EventEngine\WorkingObject\NotificationData
     */
    public function getNotificationData() {
        return $this->getContext()->getNotificationData();
    }



}
