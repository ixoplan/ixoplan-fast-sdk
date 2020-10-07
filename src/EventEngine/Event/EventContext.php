<?php

namespace Ixolit\Dislo\CDE\EventEngine\Event;

use Ixolit\Dislo\CDE\EventEngine\Thread;
use Ixolit\Dislo\CDE\EventEngine\WorkingObject\AbstractWorkingObject;
use Ixolit\Dislo\CDE\EventEngine\WorkingObject\Generic;
use Ixolit\Dislo\CDE\EventEngine\WorkingObject\Invoice;
use Ixolit\Dislo\CDE\EventEngine\WorkingObject\NotificationData;
use Ixolit\Dislo\CDE\EventEngine\WorkingObject\SubscriptionCollection;
use Ixolit\Dislo\CDE\EventEngine\WorkingObject\ThreadValueStore;
use Ixolit\Dislo\WorkingObjects\Subscription;
use Ixolit\Dislo\WorkingObjects\User;
use Ixolit\Dislo\WorkingObjects\BillingEvent;
use Ixolit\Dislo\WorkingObjects\BillingMethod;
use Ixolit\Dislo\WorkingObjects\Flexible;
use Ixolit\Dislo\WorkingObjects\Recurring;

/**
 * Class ActionContext
 * @package Ixolit\Dislo\CDE\EventEngine\Event
 */
class EventContext {

    /**
     * @var Thread
     */
    private $thread;

    /**
     * @var array
     */
    private $workingObjectsByType = array();

    /**
     * ActionContext constructor.
     * @param AbstractEvent $event
     * @param Thread $thread
     */
    public function __construct(Thread $thread, array $workingObjectsByType)
    {
        $this->thread = $thread;
        $this->setWorkingObjectsByType($workingObjectsByType);
    }

    /**
     * Map working object arrays to class representations
     * @param array $workingObjectsByType
     * @return $this
     */
    private function setWorkingObjectsByType(array $workingObjectsByType) {

        foreach($workingObjectsByType as $type => $workingObjects) {
            if (empty($this->workingObjectsByType[$type])) {
                $this->workingObjectsByType[$type] = array();
            }

            foreach($workingObjects as $data) {

                $workingObject = null;
                switch ($type) {
                    case AbstractWorkingObject::SUBSCRIPTION:
                        $workingObject = Subscription::fromResponse($data);
                        break;

                    case AbstractWorkingObject::SUBSCRIPTION_COLLECTION:
                        $workingObject = SubscriptionCollection::fromResponse($data);
                        break;

                    case AbstractWorkingObject::USER:
                        $workingObject = User::fromResponse($data);
                        break;

                    case AbstractWorkingObject::BILLING_EVENT:
                        $workingObject = BillingEvent::fromResponse($data);
                        break;

                    case AbstractWorkingObject::FLEXIBLE:
                        $workingObject = Flexible::fromResponse($data);
                        break;

                    case AbstractWorkingObject::RECURRING:
                        $workingObject = Recurring::fromResponse($data);
                        break;

                    case AbstractWorkingObject::NOTIFICATIONDATA:
                        $workingObject = NotificationData::createFromArray($data);
                        break;

                    case AbstractWorkingObject::INVOICE:
                        $workingObject = Invoice::createFromArray($data);
                        break;

                    case AbstractWorkingObject::THREADVALUESTORE:
                        $workingObject = ThreadValueStore::createFromArray($data);
                        break;

                    default:
                        $workingObject = Generic::createFromArray($data)->setType($type);

                }

                $this->workingObjectsByType[$type][] = $workingObject;

            }

        }

        return $this;
    }

    /**
     * @param string $type
     * @return array
     */
    public function getWorkingObjectsByType($type) {
        if (!array_key_exists($type, $this->workingObjectsByType)) {
            return array();
        }
        return $this->workingObjectsByType[$type];
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getFirstWorkingObjectByType($type) {
        if (!empty($this->workingObjectsByType[$type])) {
            return current($this->workingObjectsByType[$type]);
        }
        return null;
    }

    /**
     * @return bool
     */
    public function hasSubscription() {
        return $this->getSubscription() !== null;
    }

    /**
     * @return Subscription|null
     */
    public function getSubscription() {
        $subscription = $this->getFirstWorkingObjectByType(AbstractWorkingObject::SUBSCRIPTION);

        if (!($subscription instanceof Subscription)) {
            $subscriptionCollection = $this->getSubscriptionCollection();
            if ($subscriptionCollection instanceof SubscriptionCollection) {
                $subscription = $subscriptionCollection->current();
            }
        }

        return $subscription;
    }

    /**
     * @return bool
     */
    public function hasSubscriptionCollection() {
        return $this->getSubscriptionCollection() !== null;
    }

    /**
     * @return SubscriptionCollection|null
     */
    public function getSubscriptionCollection() {
        return $this->getFirstWorkingObjectByType(AbstractWorkingObject::SUBSCRIPTION_COLLECTION);
    }

    /**
     * @return bool
     */
    public function hasFlexible() {
        return $this->getFlexible() !== null;
    }

    /**
     * @return Flexible|null
     */
    public function getFlexible() {
        return $this->getFirstWorkingObjectByType(AbstractWorkingObject::FLEXIBLE);
    }

    /**
     * @return bool
     */
    public function hasThreadValueStore() {
        return $this->getThreadValueStore() !== null;
    }

    /**
     * @return ThreadValueStore|null
     */
    public function getThreadValueStore(){
        return $this->getFirstWorkingObjectByType(AbstractWorkingObject::THREADVALUESTORE);
    }

    /**
     * @return bool
     */
    public function hasInvoice() {
        return $this->getInvoice() !== null;
    }

    /**
     * @return Invoice|null
     */
    public function getInvoice() {
        return $this->getFirstWorkingObjectByType(AbstractWorkingObject::INVOICE);
    }

    /**
     * @return bool
     */
    public function hasBillingEvent() {
        return $this->getBillingEvent() !== null;
    }

    /**
     * @return BillingEvent|null
     */
    public function getBillingEvent() {
        return $this->getFirstWorkingObjectByType(AbstractWorkingObject::BILLING_EVENT);
    }

    /**
     * @return User|null
     */
    public function getUser() {
        return $this->getFirstWorkingObjectByType(AbstractWorkingObject::USER);
    }

    /**
     * @return bool
     */
    public function hasUser() {
        return $this->getUser() !== null;
    }

    /**
     * @return NotificationData|null
     */
    public function getNotificationData() {
        return $this->getFirstWorkingObjectByType(AbstractWorkingObject::NOTIFICATIONDATA);
    }

    /**
     * @return bool
     */
    public function hasNotificationData() {
        return $this->getNotificationData() !== null;
    }

    /**
     * @return bool
     */
    public function hasAccountingAccount() {
        return $this->getAccountingAccount() !== null;
    }

    /**
     * @return Generic|null
     */
    public function getAccountingAccount() {
        return $this->getFirstWorkingObjectByType(AbstractWorkingObject::ACCOUNTING_ACCOUNT);
    }

    /**
     * @return bool
     */
    public function hasRetryInfo() {
        return $this->getRetryInfo() !== null;
    }

    /**
     * @return Generic|null
     */
    public function getRetryInfo() {
        return $this->getFirstWorkingObjectByType(AbstractWorkingObject::RETRY_INFO);
    }

    /**
     * @return AbstractEvent
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @return array
     */
    public function getAllWorkingObjects() {
        return $this->workingObjectsByType;
    }

}