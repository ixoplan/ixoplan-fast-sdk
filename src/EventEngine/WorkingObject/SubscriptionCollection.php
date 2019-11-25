<?php

namespace Ixolit\Dislo\CDE\EventEngine\WorkingObject;

use Iterator;
use Ixolit\Dislo\WorkingObjects\Subscription;

/**
 * Class SubscriptionCollection
 * @package Ixolit\Dislo\CDE\EventEngine\WorkingObject
 */
class SubscriptionCollection implements WorkingObject, Iterator {

    /**
     * @var Subscription[]
     */
	private $subscriptions = [];

    /**
     * SubscriptionCollection constructor.
     * @param Subscription[] $subscriptions
     */
    public function __construct(array $subscriptions) {
        foreach($subscriptions as $subscription) {
            $this->addSubscripton($subscription);
        }
    }

    /**
     * @param Subscription $subscription
     * @return $this
     */
    public function addSubscripton(Subscription $subscription) {
        $this->subscriptions[] = $subscription;
        return $this;
    }


    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return Subscription|null
     * @since 5.0.0
     */
    public function current() {
        return current($this->subscriptions);
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next() {
        next($this->subscriptions);
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key() {
        key($this->subscriptions);
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid() {
        return key($this->subscriptions) !== null;
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind() {
        reset($this->subscriptions);
    }

    /**
     * @param array $response
     * @return SubscriptionCollection|WorkingObject|void
     */
    public static function fromResponse($response) {

        $collection = new self();

        if (!array_key_exists('subscriptions', $response)) {
            return;
        }
        
        foreach($response['subscriptions'] as $subscription) {
            $collection->addSubscripton(Subscription::fromResponse($subscription));
        }

        return $collection;
    }

    /**
     * @return array
     */
    public function toArray() {

        $this->rewind();

        return [
            'subscriptions' => array_map(function(Subscription $subscription) {
                return $subscription->toArray();
            }, $this->subscriptions)
        ];

    }

    /**
     * @param $callback
     * @return SubscriptionCollection
     */
    public function filter($callback) {
        return new self(array_filter($this->subscriptions, $callback));
    }

    /**
     * @param $callback
     * @return array
     */
    public function map($callback) {
        return array_map($callback, $this->subscriptions);
    }

    /**
     * @param $callback
     * @return $this
     */
    public function sort($callback) {
        uasort($callback);
        return $this;
    }

}