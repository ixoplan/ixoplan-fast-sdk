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
use Ixolit\Dislo\CDE\EventEngine\Event\Billing\SystemInitiatedInvoiceChargeMightFail;
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
 * Class EventRegistry
 * @package Ixolit\Dislo\CDE\EventEngine\Event
 */
class EventRegistry {

    /**
     * @var AbstractEvent[]
     */
    private static $events = array();

    /**
     * @return AbstractEvent[]
     */
    public static function getEvents() {

        if (empty(self::$events)) {

            $events = self::initEvents();
            self::$events = array_combine(
                array_map(function(AbstractEvent $event) {
                    return $event->getName();
                }, $events),
                array_map(function(AbstractEvent $event) {
                    return get_class($event);
                }, $events)
            );

        }

        return self::$events;

    }

    /**
     * @return array
     */
    private static function initEvents() {

        return array(

            //billing

            new BillingEventCreated,
            new BillingEventModified,
            new BillingEventStatusChange,
            new ChargeFailed,
            new CreditcardWillExpire,
            new FlexibleActivated,
            new SystemInitiatedChargeFailed,
            new SystemInitiatedInvoiceChargeFailed,
            new SystemInitiatedInvoiceChargeMightFail,
            new UnregisterBilling,

            //provisioning

            new ProvisionedSubscriptionCancel,
            new ProvisionedSubscriptionClose,
            new ProvisionedSubscriptionStart,
            new ProvisionedSubscriptionUpgrade,

            //subscription

            new CustomSubscriptionEvent,
            new SubscriptionCancel,
            new SubscriptionCancel,
            new SubscriptionContinue,
            new SubscriptionCreated,
            new SubscriptionExtend,
            new SubscriptionHasExpired,
            new SubscriptionPeriodEdit,
            new SubscriptionReportReceiver,
            new SubscriptionResume,
            new SubscriptionStart,
            new SubscriptionSuspend,
            new SubscriptionUpgrade,
            new SubscriptionWillExpire,

            //User
            new CustomUserEvent,
            new UserCreated,
            new UserDeleted,
            new UserMetaDataChanged,
            new UserPasswordChanged,
            new UserReportReceiver,
            new UserValidated

        );

    }

}