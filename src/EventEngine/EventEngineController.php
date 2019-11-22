<?php

namespace Ixolit\Dislo\CDE\EventEngine;

use Ixolit\Dislo\CDE\EventEngine\Event\AbstractEvent;
use Ixolit\Dislo\CDE\EventEngine\Event\EventInterface;
use Ixolit\Dislo\CDE\Ixoplan\AbstractController;
use Ixolit\Dislo\CDE\Ixoplan\Response;

/**
 * Class EventEngineController
 * @package Ixolit\Dislo\CDE\EventEngine
 */
class EventEngineController extends AbstractController {

    /**
     * @param array $availableWorkingObjects
     * @param AbstractEvent|null $event
     * @return Response
     */
    public function getAvailableActions(array $availableWorkingObjects, AbstractEvent $event=null) {

        $availableActions = [];
        $actions = ActionRegistry::get()->getActions();
        foreach($actions as $action) {
            if ($action->hasRequiredWorkingObjects($availableWorkingObjects, $event)) {
                $availableActions[] = $action->toArray();
            }
        }

        return Response::createWithSuccess($availableActions);
    }


    public function executeAction(AbstractAction $action, EventInterface $event) {
        $resultActions = $action->execute($event);

        if (!($resultActions instanceof ResultActionCollection)) {
            $resultActions = ResultActionCollection::createEmpty();
        }
        
        return Response::createWithSuccess($resultActions->toArray());
    }

}