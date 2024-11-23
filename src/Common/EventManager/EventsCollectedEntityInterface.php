<?php

namespace App\Common\EventManager;

interface EventsCollectedEntityInterface
{
    /**
     * @return EventInterface[]
     */
    public function pullEvents(): array;
}
