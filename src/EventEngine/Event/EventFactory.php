<?php

namespace Ixolit\Dislo\CDE\EventEngine\Event;

use Ixolit\Dislo\CDE\EventEngine\Event\Billing\BillingEventCreated;
use Ixolit\Dislo\CDE\EventEngine\Event\Billing\BillingEventModified;
use Ixolit\Dislo\CDE\EventEngine\Event\Billing\BillingEventStatusChange;
use Ixolit\Dislo\CDE\EventEngine\Event\Billing\ChargeFailed;
use Ixolit\Dislo\CDE\EventEngine\Event\Billing\CreditcardWillExpire;
use Ixolit\Dislo\CDE\EventEngine\Event\Billing\FlexibleActivated;
use Ixolit\Dislo\CDE\EventEngine\Event\Billing\SystemInitiatedChargeFailed;
use Ixolit\Dislo\CDE\EventEngine\Event\Billing\SystemInitiatedInvoiceChargeFailed;
use Ixolit\Dislo\CDE\EventEngine\Event\Billing\UnregisterBilling;
use Ixolit\Dislo\CDE\EventEngine\Event\Provisioning\ProvisionedSubscriptionCancel;
use Ixolit\Dislo\CDE\EventEngine\Event\Provisioning\ProvisionedSubscriptionClose;
use Ixolit\Dislo\CDE\EventEngine\Event\Provisioning\ProvisionedSubscriptionStart;
use Ixolit\Dislo\CDE\EventEngine\Event\Provisioning\ProvisionedSubscriptionUpgrade;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\CustomSubscriptionEvent;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\SubscriptionCancel;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\SubscriptionContinue;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\SubscriptionCreated;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\SubscriptionExtend;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\SubscriptionHasExpired;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\SubscriptionPeriodEdit;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\SubscriptionReportReceiver;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\SubscriptionResume;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\SubscriptionStart;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\SubscriptionSuspend;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\SubscriptionUpgrade;
use Ixolit\Dislo\CDE\EventEngine\Event\Subscription\SubscriptionWillExpire;
use Ixolit\Dislo\CDE\EventEngine\Event\User\CustomUserEvent;
use Ixolit\Dislo\CDE\EventEngine\Event\User\UserCreated;
use Ixolit\Dislo\CDE\EventEngine\Event\User\UserDeleted;
use Ixolit\Dislo\CDE\EventEngine\Event\User\UserMetaDataChanged;
use Ixolit\Dislo\CDE\EventEngine\Event\User\UserPasswordChanged;
use Ixolit\Dislo\CDE\EventEngine\Event\User\UserReportReceiver;
use Ixolit\Dislo\CDE\EventEngine\Event\User\UserValidated;

/**
 * Class EventFactory
 * @package Ixolit\Dislo\CDE\EventEngine\Event
 */
class EventFactory {

    /**
     * @param $string name
     * @return AbstractEvent
     */
    public static function createByName($name){
        $events = EventRegistry::getEvents();
        if (empty($events[$name])) {
            return new GenericEvent($name);
            //throw new \Exception("Event $name could not be mapped!");
        }

        $eventClass = $events[$name];
        return new $eventClass();
    }

}