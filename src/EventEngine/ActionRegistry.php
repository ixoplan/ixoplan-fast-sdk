<?php

namespace Ixolit\Dislo\CDE\EventEngine;

use Ixolit\Dislo\CDE\EventEngine\ActionInterface;
use Ixolit\Dislo\CDE\Ixoplan\Application;

/**
 * Class ActionRegistry
 * @package Ixolit\Dislo\CDE\EventEngine
 */
class ActionRegistry {

    /** @var self */
	private static $instance;

	/**
     * @var ActionInterface[]
     */
    private $actions = array();

	protected function __construct() {}

	private function __clone() {}

	/**
	 * @return static
	 */
	public static function get() {

		if (!isset(self::$instance)) {
			self::$instance = new static();
		}

		return self::$instance;
	}

    /**
     * @return ActionInterface[]
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * @return ActionInterface[]
     */
    public function getFullyQualifiedActions() {

        return array_combine(
            array_map(function(ActionInterface $action) {
                return get_class($action);
            }, $actions),
            array_map(function(ActionInterface $action) {
                return $action->getName();
            }, $actions)
        );

    }

    /**
     * @return $this
     */
    public function addAction(ActionInterface $action) {
        $this->actions[$action->getName()] = $action;
        return $this;
    }

    /**
     * @param array $actions
     * @return $this
     */
    public function addActions(array $actions) {
        foreach($actions as $action) {
            $this->addAction($action);
        }
        return $this;
    }

}