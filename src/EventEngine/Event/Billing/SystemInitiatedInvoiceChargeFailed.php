<?php

namespace Ixolit\Dislo\CDE\EventEngine\Event\Billing;

use Ixolit\Dislo\CDE\EventEngine\Event\AbstractEvent;

/**
 * Class SystemInitiatedInvoiceChargeFailed
 *
 * @package Ixolit\Dislo\CDE\EventEngine\Event\Billing
 */
class SystemInitiatedInvoiceChargeFailed extends AbstractEvent {

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
     * @return \Ixolit\Dislo\WorkingObjects\BillingEvent
     */
    public function getBillingEvent() {
        return $this->getContext()->getBillingEvent();
    }

    /**
     * @return \Ixolit\Dislo\CDE\EventEngine\WorkingObject\Invoice
     */
    public function getInvoice() {
        return $this->getContext()->getInvoice();
    }

    /**
     * @return \Ixolit\Dislo\CDE\EventEngine\WorkingObject\SubscriptionCollection
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
