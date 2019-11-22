<?php

namespace Ixolit\Dislo\CDE\EventEngine\Event\Billing;

use Ixolit\Dislo\CDE\EventEngine\Event\AbstractEvent;
use Ixolit\Dislo\CDE\EventEngine\WorkingObject\Generic;

/**
 * Class UnregisterBilling
 * Will be triggered directly prior to closing the flexible
 *
 * @package Ixolit\Dislo\CDE\EventEngine\Event\Billing
 */
class UnregisterBilling extends AbstractEvent {

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
     * @return Generic
     */
    public function getAccountingAccount() {
        return $this->getContext()->getAccountingAccount();
    }

}
