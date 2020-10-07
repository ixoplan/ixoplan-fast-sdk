<?php


namespace Ixolit\Dislo\CDE\Ixoplan;

use Ixolit\Dislo\CDE\EventEngine\AbstractAction;
use Ixolit\Dislo\CDE\EventEngine\Event\EventFactory;
use Ixolit\Dislo\CDE\EventEngine\Event\EventContext;
use Ixolit\Dislo\CDE\EventEngine\EventEngineController;
use Ixolit\Dislo\CDE\EventEngine\Thread;

/**
 * Class Router
 * @package Ixolit\Dislo\CDE\Ixoplan
 */
class Router {

    /**
     * @param Request $request
     * @return Response
     */
    public static function route(Request $request) {

        $routes = [
            '/event-engine/getAvailableActions' => function(Request $request) {
                $controller = new EventEngineController($request);
                
                $event = $request->getParameters()->getOptional('event');
                if ($event) {
                    $event = EventFactory::createByName($event);
                }
                
                return $controller->getAvailableActions($request->getParameters()->workingObjects, $event);
            },
            
            '/event-engine/executeAction' => function(Request $request) {
                $controller = new EventEngineController($request);

                $action = $request->getParameters()->action;
                if (!class_exists($action)) {
                    throw new \Exception("Class $action not found!");
                }
                $action = new $action();
                /** @var $action AbstractAction */

                $thread = Thread::fromArray($request->getParameters()->thread);
                $workingObjects = $request->getParameters()->workingObjects;

                $eventContext = new EventContext($thread, $workingObjects);

                $event = EventFactory::createByName($request->getParameters()->event);
                $event->setContext($eventContext);

                return $controller->executeAction($action, $event);
            },
            
        ];

        if (empty($routes[$request->getPath()])) {
            return Response::createWithError(404, "No route defined for path '" . $request->getPath() . "'");
        }

        return $routes[$request->getPath()]($request);
    }

}