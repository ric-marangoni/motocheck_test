<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Infrastructure;

use Broadway\EventHandling\SimpleEventBus;
use Interop\Container\ContainerInterface;
//use MMLabs\Core\Middleware\LogEventsMiddleware;
//use MMLabs\Core\ServiceBus\Adapter\BroadwayEventBusMiddleware;
//use MMLabs\Core\ServiceBus\EventBus\EventBusSupportingMiddleware;

final class EventBusFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $eventBus = new SimpleEventBus;
        $listeners = $container->get('config')['event_bus']['listeners'];

        foreach ($listeners as $handlerService) {
            $eventBus->subscribe($container->get($handlerService));
        }

        $eventBusSupportingMiddleware = new EventBusSupportingMiddleware();
        $eventBusSupportingMiddleware->appendMiddleware(new LogEventsMiddleware($container->get('logger')));
        $eventBusSupportingMiddleware->appendMiddleware(new BroadwayEventBusMiddleware($eventBus));

        return $eventBusSupportingMiddleware;
    }
}
