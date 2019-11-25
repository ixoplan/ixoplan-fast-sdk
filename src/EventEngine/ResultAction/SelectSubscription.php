<?php

namespace Ixolit\Dislo\CDE\EventEngine\ResultAction;

/**
 * Class SelectSubscription
 * Result action to select one specific subscription within an SubscriptionCollection
 * All subscription action/conditions within the event engine are then working on that selected subscription.
 */
class SelectSubscription extends AbstractResultAction {

    /**
     * @var int
     */
    protected $subscriptionId;

    /**
     * SelectSubscription constructor.
     * @param $subscriptionId
     */
    public function __construct($subscriptionId){
        $this->subscriptionId = (int) $subscriptionId;
    }


}