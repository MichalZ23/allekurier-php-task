<?php

declare(strict_types=1);

namespace App\Common\EventManager;

use Psr\EventDispatcher\EventDispatcherInterface;

class EventsCollectorDispatcher
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function dispatchEntityEvents(EventsCollectedEntityInterface $entity): void
    {
        foreach ($entity->pullEvents() as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
