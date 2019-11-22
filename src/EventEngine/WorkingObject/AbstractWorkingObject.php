<?php

namespace Ixolit\Dislo\CDE\EventEngine\WorkingObject;

/**
 * Class AbstractWorkingObject
 * @package Ixolit\Dislo\CDE\EventEngine
 */
abstract class AbstractWorkingObject {

    //WorkingObjects
    const USER = 'User';
    const SUBSCRIPTION = 'Subscription';
    const SUBSCRIPTION_COLLECTION = 'SubscriptionCollection';
    const INVOICE = 'Invoice';
    const BILLING_EVENT = 'BillingEvent';
    const THREADVALUESTORE = 'ThreadValueStore';
    const NOTIFICATIONDATA = 'NotificationData';
    const FLEXIBLE = 'Flexible';
    const RECURRING = 'Recurring';
    const ACCOUNTING_ACCOUNT = 'AccountingAccount';
    const RETRY_INFO = 'RetryInfo';

    /**
     * @var array
     */
    protected $data = array();

    /**
     * AbstractWorkingObject constructor.
     * @param array $data
     */
    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * Gets an untyped member of this
     *
     * @param string $key the member name to fetch.
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default=null) {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return $default;
        }
    }

    /**
	 * Fetches an untyped member of this EJP object.
	 *
	 * @param string $key   the member name to set
	 * @param mixed  $value the value to set.
	 *
	 * @return $this
	 */
	public function set($key, $value) {
		$this->data[$key] = $value;
		return $this;
	}

	/**
	 * Static wrapper function for AbstractWorkingObject::fromArray(), creates this object from
	 * an array.
	 *
	 * @param array $data data to convert.
	 *
	 * @return static
	 */
	public static function createFromArray($data = array()) {
		$class = get_called_class();
		return new $class($data);
	}

}